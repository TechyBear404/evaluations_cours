<template>
    <Head title="Enquêtes" />
    <AppLayout>
        <div class="container p-6 mx-auto">
            <Card
                class="transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
            >
                <CardHeader>
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-lg bg-primary/10">
                            <font-awesome-icon
                                icon="fa-solid fa-list-check"
                                class="text-xl text-primary"
                            />
                        </div>
                        <div class="space-y-1">
                            <CardTitle class="text-2xl font-bold">
                                Liste des enquêtes
                            </CardTitle>
                            <CardDescription class="text-base">
                                Voir toutes les évaluations de cours en cours
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-if="surveys.length > 0" class="grid gap-4">
                            <Link
                                v-for="survey in surveys"
                                :key="survey.id"
                                :href="route('survey.details', survey.id)"
                                class="block transition-all hover:shadow-md"
                            >
                                <div
                                    class="p-4 transition-colors border rounded-lg hover:bg-secondary/5 hover:border-primary"
                                >
                                    <div
                                        class="flex items-center justify-between gap-4"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="p-2 rounded-md bg-primary/10"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-book"
                                                    class="text-primary"
                                                />
                                            </div>
                                            <h3 class="font-semibold">
                                                {{ survey.course.name }}
                                            </h3>
                                        </div>
                                        <div
                                            class="flex items-center gap-6 text-sm text-muted-foreground"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-calendar-day"
                                                />
                                                <span>{{
                                                    formatDate(
                                                        survey.start_date
                                                    )
                                                }}</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-calendar-check"
                                                />
                                                <span>{{
                                                    formatDate(survey.end_date)
                                                }}</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-2 text-primary"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-users"
                                                />
                                                <span
                                                    >{{
                                                        survey.responses_count
                                                    }}
                                                    /
                                                    {{
                                                        survey.students_count
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        <div
                            v-else
                            class="p-8 text-center text-muted-foreground"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-clipboard-question"
                                class="mb-2 text-3xl text-primary/50"
                            />
                            <p>Aucune enquête n'a été trouvée.</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { formatDate } from "@/lib/utils";
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from "@/Components/ui/card";

const props = defineProps({
    surveys: Array,
});
</script>
