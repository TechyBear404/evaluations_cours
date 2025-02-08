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
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/Components/ui/card";
import { Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import { formatDate } from "@/lib/utils";
import Badge from "@/Components/ui/badge/Badge.vue";
import Input from "@/Components/ui/input/Input.vue";

const props = defineProps({
    courses: {
        type: Array,
    },
    years: {
        type: Array,
        required: true,
    },
});

const form = useForm({});

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
const searchQuery = ref("");

// Add watch effect to save to localStorage
watch(selectedYear, (newValue) => {
    localStorage.setItem(SELECTED_YEAR, newValue);
});

const filteredCourses = computed(() => {
    let filtered = props.courses;

    if (selectedYear.value && selectedYear.value !== "all") {
        filtered = filtered.filter(
            (course) => course.year_id == selectedYear.value
        );
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter((course) =>
            course.name.toLowerCase().includes(query)
        );
    }

    return filtered;
});

const sendForm = (courseId) => {
    form.post(`/courses/${courseId}/send-form`, {
        preserveScroll: true,
        onSuccess: () => {
            // console.log("Formulaire envoyé avec succès");
        },
        onError: (errors) => {
            // console.log(errors);
        },
    });
};

const getRandomColor = () => {
    const colors = [
        "text-red-400",
        "text-blue-400",
        "text-green-400",
        "text-yellow-400",
        "text-purple-400",
        "text-pink-400",
        "text-indigo-400",
    ];
    return colors[Math.floor(Math.random() * colors.length)];
};
</script>
<template>
    <AppLayout>
        <div class="container p-6 mx-auto">
            <Card
                class="transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
            >
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-lg bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-graduation-cap"
                                    class="text-xl text-primary"
                                />
                            </div>
                            <div class="space-y-1">
                                <CardTitle class="text-2xl font-bold"
                                    >Liste des cours</CardTitle
                                >
                                <CardDescription class="text-base">
                                    Gérer les cours et leurs évaluations
                                </CardDescription>
                            </div>
                        </div>
                        <Link :href="route('courses.create')">
                            <Button class="gap-2">
                                <font-awesome-icon icon="fa-solid fa-plus" />
                                Ajouter un cours
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="flex gap-4 mb-6">
                        <Select v-model="selectedYear" class="w-[200px]">
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
                                    :value="year.id"
                                >
                                    {{ year.year }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <Input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Rechercher un cours..."
                            class="max-w-sm"
                        />
                    </div>

                    <div class="space-y-4">
                        <div
                            v-if="filteredCourses.length === 0"
                            class="flex flex-col items-center justify-center py-12"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-book"
                                class="mb-4 text-4xl text-primary/40"
                            />
                            <h3 class="text-lg font-medium">
                                Aucun cours trouvé
                            </h3>
                            <p class="text-muted-foreground">
                                Aucun cours n'est disponible pour cette année.
                            </p>
                        </div>

                        <div v-else class="grid gap-4">
                            <div
                                v-for="course in filteredCourses"
                                :key="course.id"
                            >
                                <div
                                    class="p-4 transition-all border rounded-lg hover:shadow-md hover:border-primary group"
                                >
                                    <Link
                                        :href="route('courses.show', course.id)"
                                        class="block"
                                    >
                                        <div
                                            class="flex items-center justify-between"

                                        >
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="p-2 rounded-md bg-primary/10"
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-solid fa-book"
                                                        class="text-primary"
                                                    />
                                                </div>
                                                <div>
                                                    <h3 class="font-semibold">
                                                        {{ course.name }}
                                                    </h3>
                                                    <div
                                                        class="flex items-center gap-2 mt-1 text-sm text-muted-foreground"
                                                    >
                                                        <font-awesome-icon
                                                            icon="fa-solid fa-calendar"
                                                        />
                                                        <span
                                                            >{{
                                                                formatDate(
                                                                    course.start_date
                                                                )
                                                            }}
                                                            -
                                                            {{
                                                                formatDate(
                                                                    course.end_date
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <Badge variant="secondary">
                                                    {{ course.students.length }}
                                                    inscrits
                                                </Badge>
                                                <Button
                                                    v-if="!course.is_sent"
                                                    variant="outline"
                                                    @click.prevent.stop="
                                                        sendForm(course.id)
                                                    "
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-solid fa-paper-plane"
                                                        class="mr-2"
                                                    />
                                                    Envoyer
                                                </Button>
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
