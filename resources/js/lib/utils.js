import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";
import jsPDF from "jspdf";
import "jspdf-autotable";
import * as XLSX from "xlsx";
import {
    Document,
    Paragraph,
    Table,
    TableRow,
    TableCell,
    HeadingLevel,
    Packer,
    WidthType,
    BorderStyle,
} from "docx";
import { saveAs } from "file-saver";

export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

export function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

const truncateSheetName = (name) => {
    return name
        .replace(/[^\w\s-]/g, "")
        .replace(/\s+/g, "_")
        .substring(0, 31);
};

export async function generateFile({ title, headers, rows, format }) {
    const columnStyles = {};
    headers.forEach((header, index) => {
        columnStyles[index] = { cellWidth: "40" };
    });

    try {
        if (format === "pdf") {
            const doc = new jsPDF({
                format: "a4",
                orientation: "landscape",
            });
            doc.text(title, 20, 20);
            doc.autoTable({
                startY: 30,
                head: [headers],
                body: rows,
                styles: {
                    cellPadding: 2,
                    fontSize: 10,
                    overflow: "linebreak",
                    cellWidth: "wrap",
                    halign: "center",
                },
                columnStyles,
                tableWidth: "wrap", // Force le tableau à rester dans les limites
                didDrawCell: (data) => {
                    // Casse les mots longs
                    if (data.section === "head") {
                        data.cell.text = data.cell.text
                            .join(" ")
                            .match(/.{1,8}/g)
                            .join("\n");
                    }
                },
            });
            return doc.output("arraybuffer");
        } else if (format === "xlsx") {
            const wb = XLSX.utils.book_new();

            for (let i = 0; i < rows.length; i++) {
                rows[0].unshift(`Etudiant ${i + 1}`);
            }

            const wsData = [[title], [], ["Questions", ...headers], ...rows];

            const ws = XLSX.utils.aoa_to_sheet(wsData);
            ws["!cols"] = [{ wch: title.length }];

            let colones = [];
            for (let i = 2; i < wsData.length; i++) {
                for (let j = 0; j < wsData[i].length; j++) {
                    if (i === 2) {
                        colones.push([wsData[i][j]]);
                    } else {
                        colones[j].push(wsData[i][j]);
                    }
                }
            }
            for (let i = 1; i < colones.length; i++) {
                let maxLength = 0;
                for (let j = 0; j < colones[i].length; j++) {
                    if (colones[i][j].length > maxLength) {
                        maxLength = colones[i][j].length - 5;
                    }
                }
                // ws["!cols"] = ws["!cols"] || [];
                ws["!cols"].push({ wch: maxLength });
            }

            const sheetName = truncateSheetName(title);
            XLSX.utils.book_append_sheet(wb, ws, sheetName);
            console.log("test");
            return XLSX.write(wb, { bookType: "xlsx", type: "array" });
        } else if (format === "docx") {
            const columnWidth = Math.floor(9000 / headers.length);

            const doc = new Document({
                sections: [
                    {
                        properties: {
                            page: {
                                margin: {
                                    top: 1000,
                                    right: 1000,
                                    bottom: 1000,
                                    left: 1000,
                                },
                            },
                        },
                        children: [
                            new Paragraph({
                                text: title,
                                heading: HeadingLevel.HEADING_1,
                                spacing: { after: 400 },
                            }),
                            new Table({
                                width: {
                                    size: 100,
                                    type: WidthType.PERCENTAGE,
                                },
                                columnWidths: Array(headers.length).fill(
                                    columnWidth
                                ),
                                rows: [
                                    new TableRow({
                                        children: headers.map(
                                            (header) =>
                                                new TableCell({
                                                    width: {
                                                        size: columnWidth,
                                                        type: WidthType.DXA,
                                                    },
                                                    children: [
                                                        new Paragraph({
                                                            text: header,
                                                            bold: true,
                                                        }),
                                                    ],
                                                    borders: {
                                                        bottom: {
                                                            style: BorderStyle.SINGLE,
                                                            size: 1,
                                                        },
                                                    },
                                                })
                                        ),
                                    }),
                                    ...rows.map(
                                        (row) =>
                                            new TableRow({
                                                children: row.map(
                                                    (cell) =>
                                                        new TableCell({
                                                            width: {
                                                                size: columnWidth,
                                                                type: WidthType.DXA,
                                                            },
                                                            children: [
                                                                new Paragraph({
                                                                    text:
                                                                        cell ||
                                                                        "",
                                                                    spacing: {
                                                                        before: 100,
                                                                        after: 100,
                                                                    },
                                                                }),
                                                            ],
                                                        })
                                                ),
                                            })
                                    ),
                                ],
                            }),
                        ],
                    },
                ],
            });
            return await Packer.toBuffer(doc);
        }
    } catch (error) {
        console.error(`Error generating ${format} file:`, error);
        throw error;
    }
}

export function formatSurveyDataForExport(surveyData, courseName) {
    // Get questions and ensure they're valid
    const headers = surveyData.questions.map((q) => q.label || "");

    // Format rows with proper error handling
    const rows = surveyData.responses.map((responseRow) =>
        surveyData.questions.map((question) => {
            try {
                const responses = responseRow[question.id] || [];
                const formattedResponses = responses
                    .map((response) => {
                        if (!response) return "";
                        return response.option?.name
                            ? `${response.option.name}: ${
                                  response.content || ""
                              }`
                            : response.content || "";
                    })
                    .filter(Boolean); // Remove empty responses

                return formattedResponses.length > 0
                    ? formattedResponses.join(" | ")
                    : "";
            } catch (err) {
                console.error("Error formatting response:", err);
                return "";
            }
        })
    );

    return {
        title: `Évaluation - ${courseName || "Cours"}`,
        headers,
        rows,
    };
}
