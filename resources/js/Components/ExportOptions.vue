<template>
    <div class="flex flex-col gap-4">
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    class="justify-start w-full transition-colors hover:bg-primary/10 hover:border-primary group"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-download"
                        class="mr-2 transition-transform text-primary group-hover:scale-110"
                    />
                    Télécharger le rapport
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-48">
                <DropdownMenuItem
                    @click="handleFileGeneration('pdf')"
                    class="cursor-pointer hover:bg-red-50"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-file-pdf"
                        class="mr-2 text-red-500"
                    />
                    Format PDF
                </DropdownMenuItem>
                <DropdownMenuItem
                    @click="handleFileGeneration('xlsx')"
                    class="cursor-pointer hover:bg-green-50"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-file-excel"
                        class="mr-2 text-green-500"
                    />
                    Format Excel
                </DropdownMenuItem>
                <DropdownMenuItem
                    @click="handleFileGeneration('docx')"
                    class="cursor-pointer hover:bg-blue-50"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-file-word"
                        class="mr-2 text-blue-500"
                    />
                    Format Word
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>

        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    class="justify-start w-full transition-colors hover:bg-primary/10 hover:border-primary group"
                    :disabled="!teacher"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-envelope"
                        class="mr-2 transition-transform text-primary group-hover:scale-110"
                    />
                    <span v-if="teacher">
                        Envoyer par email à {{ teacher.firstname }}
                        {{ teacher.lastname }}
                    </span>
                    <span v-else> Pas de professeur</span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-48">
                <DropdownMenuItem
                    @click="handleFileGeneration('pdf', true)"
                    class="cursor-pointer hover:bg-red-50"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-file-pdf"
                        class="mr-2 text-red-500"
                    />
                    Format PDF
                </DropdownMenuItem>
                <DropdownMenuItem
                    @click="handleFileGeneration('xlsx', true)"
                    class="cursor-pointer hover:bg-green-50"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-file-excel"
                        class="mr-2 text-green-500"
                    />
                    Format Excel
                </DropdownMenuItem>
                <DropdownMenuItem
                    @click="handleFileGeneration('docx', true)"
                    class="cursor-pointer hover:bg-blue-50"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-file-word"
                        class="mr-2 text-blue-500"
                    />
                    Format Word
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>

<script setup>
import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
} from "@/Components/ui/dropdown-menu";
import { useForm } from "@inertiajs/vue3";
import { saveAs } from "file-saver";
import { generateFile } from "@/lib/utils";

const props = defineProps({
    surveyId: {
        type: [Number, String],
        required: true,
    },
    courseName: {
        type: String,
        required: true,
    },
    exportData: {
        type: Object,
        required: true,
    },
    teacher: {
        type: Object,
        required: true,
    },
    dateRange: {
        type: String,
        required: true,
    },
});

const form = useForm({
    format: "",
    fileContent: "",
});

const handleFileGeneration = async (format, isEmail = false) => {
    const { title, headers, rows } = props.exportData;
    try {
        const fileContent = await generateFile({
            title,
            headers,
            rows,
            format,
        });
        const filename = `evaluation_${props.courseName}_${props.dateRange}.${format}`;

        if (isEmail) {
            // Convert ArrayBuffer to base64 string
            const buffer = new Uint8Array(fileContent);
            const base64Content = btoa(
                Array.from(buffer)
                    .map((byte) => String.fromCharCode(byte))
                    .join("")
            );

            form.fileContent = base64Content;
            form.format = format;

            form.post(`/survey/${props.surveyId}/send-exported-file`, {
                onSuccess: () => {},
                onError: (errors) => {
                    console.error("Email sending error:", errors);
                },
            });
        } else {
            saveAs(new Blob([fileContent]), filename);
        }
    } catch (error) {
        alert("Erreur lors de la génération du fichier");
    }
};
</script>
