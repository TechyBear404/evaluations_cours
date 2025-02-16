<template>
    <Head :title="'Détails de l\'enquête : ' + survey.course.name" />
    <AppLayout>
        <div class="container p-6 mx-auto">
            <div class="space-y-8">
                <div class="grid gap-6 md:grid-cols-3">
                    <Card
                        class="transition-shadow border-l-4 shadow-lg md:col-span-2 border-primary hover:shadow-xl"
                    >
                        <CardHeader>
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-lg bg-primary/10">
                                    <font-awesome-icon
                                        icon="fa-solid fa-book"
                                        class="text-xl text-primary"
                                    />
                                </div>
                                <div class="space-y-1">
                                    <CardTitle class="text-2xl font-bold">
                                        {{ survey.course.name }}
                                    </CardTitle>
                                    <CardDescription class="text-base">
                                        Rapport d'évaluation détaillé du cours
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="grid gap-6 mt-4 md:grid-cols-2">
                                <div
                                    class="p-4 transition-colors rounded-lg bg-primary/10 hover:bg-primary/20"
                                >
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="p-2 rounded-md bg-primary">
                                            <font-awesome-icon
                                                icon="fa-solid fa-calendar-day"
                                                class="text-primary-foreground"
                                            />
                                        </div>
                                        <h3 class="font-semibold">
                                            Période d'évaluation
                                        </h3>
                                    </div>
                                    <div class="mt-2">
                                        <div
                                            class="flex flex-col items-baseline gap-2 ml-4"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-calendar-day"
                                                    class="text-muted-foreground"
                                                />
                                                <p
                                                    class="text-sm text-muted-foreground"
                                                >
                                                    Début:
                                                </p>
                                                <p class="text-sm font-medium">
                                                    {{
                                                        formatDate(
                                                            survey.start_date,
                                                            "short"
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-calendar-check"
                                                    class="text-muted-foreground"
                                                />
                                                <p
                                                    class="text-sm text-muted-foreground"
                                                >
                                                    Fin:
                                                </p>
                                                <p class="text-sm font-medium">
                                                    {{
                                                        formatDate(
                                                            survey.end_date,
                                                            "short"
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="p-4 transition-colors rounded-lg bg-primary/10 hover:bg-primary/20"
                                >
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="p-2 rounded-md bg-primary">
                                            <font-awesome-icon
                                                icon="fa-solid fa-chart-simple"
                                                class="text-primary-foreground"
                                            />
                                        </div>
                                        <h3 class="font-semibold">
                                            Statistiques des réponses
                                        </h3>
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-baseline gap-2">
                                            <span
                                                class="text-3xl font-bold text-primary"
                                                >{{
                                                    survey.responses_count
                                                }}</span
                                            >
                                            <span
                                                class="text-sm text-muted-foreground"
                                                >réponses reçues</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card
                        class="transition-all border-t-4 shadow-md border-primary hover:shadow-lg"
                    >
                        <CardHeader>
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-lg bg-primary/10">
                                    <font-awesome-icon
                                        icon="fa-solid fa-download"
                                        class="text-xl text-primary"
                                    />
                                </div>
                                <div class="space-y-1">
                                    <CardTitle class="text-2xl font-bold">
                                        Export
                                    </CardTitle>
                                    <CardDescription class="text-base">
                                        Téléchargez le rapport dans différents
                                        formats
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <ExportOptions
                                :teacher="survey.course.teacher"
                                :survey-id="survey.id"
                                :course-name="survey.course.name"
                                :export-data="formatDataForExport"
                                :date-range="date_range"
                            />
                        </CardContent>
                    </Card>
                </div>

                <!-- Responses -->
                <Card
                    class="transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
                >
                    <CardHeader>
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-lg bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-table"
                                    class="text-xl text-primary"
                                />
                            </div>
                            <div class="space-y-1">
                                <CardTitle class="text-2xl font-bold">
                                    Réponses
                                </CardTitle>
                                <CardDescription class="text-base">
                                    Tableau des réponses des étudiants
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="survey.responses_count === 0"
                            class="flex flex-col items-center justify-center p-8 space-y-4 text-center"
                        >
                            <div class="p-4 rounded-full bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-inbox"
                                    class="text-3xl text-primary"
                                />
                            </div>
                            <h3 class="text-lg font-semibold text-primary">
                                Aucune réponse pour le moment
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                Les réponses des étudiants apparaîtront ici une
                                fois qu'ils auront complété l'évaluation.
                            </p>
                        </div>
                        <div
                            v-else
                            class="overflow-x-auto border border-gray-200 rounded-lg"
                        >
                            <Table class="w-full">
                                <TableHeader>
                                    <TableRow
                                        class="border-b bg-primary/10 border-primary/20"
                                    >
                                        <TableHead
                                            v-for="question in surveyData.questions"
                                            :key="question.id"
                                            class="px-4 py-3 text-sm font-semibold text-primary whitespace-nowrap"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                {{ question.label }}
                                            </div>
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="(
                                            responseRow, index
                                        ) in surveyData.responses"
                                        :key="responseRow.studentId"
                                        class="transition-colors hover:bg-secondary/5 even:bg-gray-50/50"
                                    >
                                        <TableCell
                                            v-for="question in surveyData.questions"
                                            :key="question.id"
                                            class="p-4 border-b border-gray-100"
                                        >
                                            <div class="space-y-2">
                                                <div
                                                    v-for="(
                                                        response, idx
                                                    ) in responseRow[
                                                        question.id
                                                    ]"
                                                    :key="idx"
                                                    class="py-1"
                                                >
                                                    <div
                                                        v-if="
                                                            response.option
                                                                ?.name
                                                        "
                                                        class="flex flex-col gap-1"
                                                    >
                                                        <span
                                                            class="text-sm font-medium text-primary"
                                                        >
                                                            {{
                                                                response.option
                                                                    .name
                                                            }}
                                                        </span>
                                                        <span
                                                            class="text-sm text-gray-600"
                                                        >
                                                            {{
                                                                response.content
                                                            }}
                                                        </span>
                                                    </div>
                                                    <div
                                                        v-else
                                                        class="text-sm text-gray-700"
                                                    >
                                                        {{ response.content }}
                                                    </div>
                                                </div>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    Table,
    TableHeader,
    TableBody,
    TableHead,
    TableRow,
    TableCell,
} from "@/Components/ui/table";
import { formatDate, formatSurveyDataForExport } from "@/lib/utils";
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from "@/Components/ui/card";
import ExportOptions from "@/Components/ExportOptions.vue";
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    survey: Object,
});

const date_range =
    new Date(props.survey.course.start_date)
        .getFullYear()
        .toString()
        .slice(-2) +
    "-" +
    new Date(props.survey.course.end_date).getFullYear().toString().slice(-2);

const surveyData = computed(() => {
    // Get unique questions using a Map to handle duplicates by ID
    const questionsMap = new Map();
    props.survey.responses.forEach((r) => {
        if (!questionsMap.has(r.question.id)) {
            questionsMap.set(r.question.id, r.question);
        }
    });

    const questions = Array.from(questionsMap.values()).sort(
        (a, b) => a.order - b.order
    );

    // Group responses by student and question
    const responsesByStudent = props.survey.responses.reduce(
        (acc, response) => {
            if (!acc[response.student_id]) {
                acc[response.student_id] = {};
            }
            if (!acc[response.student_id][response.question_id]) {
                acc[response.student_id][response.question_id] = [];
            }
            acc[response.student_id][response.question_id].push(response);
            return acc;
        },
        {}
    );

    const responses = Object.entries(responsesByStudent).map(
        ([studentId, questionResponses]) => ({
            studentId: parseInt(studentId),
            ...questionResponses,
        })
    );

    return {
        questions,
        responses,
    };
});

const formatDataForExport = computed(() =>
    formatSurveyDataForExport(surveyData.value, props.survey.course.name)
);
</script>
