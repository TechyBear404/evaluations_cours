<script setup>
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import Button from "@/Components/ui/button/Button.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Card, CardContent } from "@/Components/ui/card";
import { Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { formatDate } from "@/lib/utils";

const props = defineProps({
    courses: {
        type: Array,
    },
    years: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    teacher_id: "",
});

const SELECTED_YEAR = "selected_year";

const getCurrentYearId = () => {
    const savedYear = localStorage.getItem(SELECTED_YEAR);
    if (savedYear === "all") return "all";
    if (savedYear) return parseInt(savedYear);

    const currentYear = new Date().getFullYear();
    const yearObj = props.years.find((y) => y.year == currentYear);

    if (yearObj) return yearObj.id;

    // Si l'année courante n'existe pas, trouve l'année la plus proche
    return props.years.reduce((closest, year) => {
        if (!closest) return year;

        const currentDiff = Math.abs(year.year - currentYear);
        const closestDiff = Math.abs(closest.year - currentYear);

        return currentDiff < closestDiff ? String(year) : String(closest);
    }, null)?.id;
};

const selectedYear = ref(getCurrentYearId());

// Add watch effect to save to localStorage
watch(selectedYear, (newValue) => {
    localStorage.setItem(SELECTED_YEAR, newValue);
});

const filteredCourses = computed(() => {
    if (!selectedYear.value || selectedYear.value === "all")
        return props.courses;
    return props.courses.filter(
        (course) => course.year_id == selectedYear.value
    );
});

const sendForm = (courseId) => {
    form.get(`/courses/${courseId}/send-form`, {
        preserveScroll: true,
        onSuccess: () => {
            console.log("Formulaire envoyé avec succès");
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};
</script>
<template>
    <AppLayout>
        <div class="container p-6 mx-auto">
            <h1 class="mb-6 text-3xl font-bold text-gray-900">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div>Liste des cours</div>
                        <Select v-model="selectedYear">
                            <SelectTrigger class="w-[280px]">
                                <SelectValue placeholder="Choisir une année" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">
                                    Tous les cours
                                </SelectItem>
                                <SelectItem
                                    v-for="year in props.years"
                                    :key="year.id"
                                    :value="String(year.id)"
                                >
                                    {{ year.year }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <Link :href="`/courses/create`">
                        <Button variant="default" size="sm">
                            <font-awesome-icon
                                icon="fa-solid fa-plus"
                                class="mr-2"
                            />
                            Ajouter un cours
                        </Button>
                    </Link>
                </div>
            </h1>

            <!-- Empty state -->
            <div v-if="filteredCourses.length === 0" class="py-12 text-center">
                <div class="flex flex-col items-center space-y-4">
                    <font-awesome-icon
                        icon="fa-solid fa-book"
                        class="text-4xl text-gray-400"
                    />
                    <h3 class="text-lg font-medium text-gray-900">
                        Aucun cours trouvé
                    </h3>
                    <p class="text-gray-500">
                        Aucun cours n'est disponible pour cette année.
                    </p>
                </div>
            </div>

            <!-- Courses list -->
            <div v-else class="space-y-4">
                <div
                    v-for="course in filteredCourses"
                    :key="course.id"
                    class="block hover:no-underline"
                >
                    <Card class="transition-colors hover:bg-gray-50">
                        <CardContent
                            class="flex items-center justify-between gap-4 py-4"
                        >
                            <Link
                                class="flex items-center justify-between w-full space-x-4"
                                :href="`/courses/${course.id}`"
                            >
                                <div class="flex items-center gap-2">
                                    <font-awesome-icon
                                        icon="fa-solid fa-book"
                                        class="text-xl text-gray-500"
                                    />
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ course.name }}
                                    </h3>
                                </div>
                                <div class="flex gap-2">
                                    <p>Période :</p>
                                    <p>{{ formatDate(course.start_date) }}</p>
                                    <p>au</p>
                                    <p>{{ formatDate(course.end_date) }}</p>
                                </div>
                            </Link>
                            <div class="flex items-center space-x-4">
                                <Button
                                    v-if="!course.is_sent"
                                    variant="outline"
                                    class="ml-4"
                                    @click.prevent.stop="sendForm(course.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-paper-plane"
                                        class="mr-2"
                                    />
                                    Envoyer
                                </Button>
                                <Button
                                    v-if="course.is_sent"
                                    variant="outline"
                                    class="ml-4"
                                    :disabled="course.form_id === null"
                                >
                                    Générer le rapport
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
