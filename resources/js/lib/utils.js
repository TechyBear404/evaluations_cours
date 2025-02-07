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
    try {
        if (format === "pdf") {
            const doc = new jsPDF();
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
                },
                columnStyles: { 0: { cellWidth: 40 } },
            });
            return doc.output("arraybuffer");
        } else if (format === "xlsx") {
            const wb = XLSX.utils.book_new();
            const wsData = [headers, ...rows];
            const ws = XLSX.utils.aoa_to_sheet(wsData);
            const sheetName = truncateSheetName(title);
            XLSX.utils.book_append_sheet(wb, ws, sheetName);
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
