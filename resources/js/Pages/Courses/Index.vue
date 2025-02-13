<template>
    <Head title="Cours" />
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
                            <Button class="gap-2" title="Ajouter un cours">
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
                        <div class="flex items-center space-x-2">
                            <Switch @click="toggleSwitchIsSentCourse" />
                            <span class="text-sm text-muted-foreground"
                                >Cours envoyés uniquement</span
                            >
                        </div>
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
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div
                                            class="flex-1 cursor-pointer"
                                            @click="navigateToCourse(course.id)"
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
                                                        <h3
                                                            class="font-semibold"
                                                        >
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
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <Badge variant="secondary">
                                                {{ course.students.length }}
                                                inscrits
                                            </Badge>
                                            <Button
                                                v-if="!course.is_sent"
                                                variant="outline"
                                                @click="sendForm(course.id)"
                                                class="min-w-28"
                                                title="Envoyer le formulaire"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-paper-plane"
                                                    class="mr-2"
                                                />
                                                Envoyer
                                            </Button>
                                            <Button
                                                v-else
                                                variant="outline"
                                                @click="
                                                    navigateToSurvey(course)
                                                "
                                                class="min-w-28"
                                                title="Voir les résultats"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-clipboard-list"
                                                    class="mr-2"
                                                />
                                                Voir
                                            </Button>
                                            <Dialog>
                                                <DialogTrigger asChild>
                                                    <Button
                                                        variant="outline"
                                                        class="text-red-500 hover:text-red-50 hover:bg-red-500"
                                                        title="Supprimer le cours"
                                                    >
                                                        <font-awesome-icon
                                                            icon="fa-solid fa-trash"
                                                        />
                                                    </Button>
                                                </DialogTrigger>
                                                <DialogContent>
                                                    <DialogHeader>
                                                        <DialogTitle
                                                            >Êtes-vous sûr
                                                            ?</DialogTitle
                                                        >
                                                        <DialogDescription>
                                                            Cette action est
                                                            irréversible. Le
                                                            cours et toutes les
                                                            données associées
                                                            seront
                                                            définitivement
                                                            supprimés.
                                                        </DialogDescription>
                                                    </DialogHeader>
                                                    <DialogFooter>
                                                        <DialogClose asChild>
                                                            <Button
                                                                variant="secondary"
                                                                >Annuler</Button
                                                            >
                                                        </DialogClose>
                                                        <Button
                                                            variant="destructive"
                                                            @click="
                                                                deleteCourse(
                                                                    course.id
                                                                )
                                                            "
                                                        >
                                                            Supprimer
                                                        </Button>
                                                    </DialogFooter>
                                                </DialogContent>
                                            </Dialog>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

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
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Link, useForm, router, Head } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import { formatDate } from "@/lib/utils";
import Badge from "@/Components/ui/badge/Badge.vue";
import Input from "@/Components/ui/input/Input.vue";
import { Switch } from "@/Components/ui/switch";

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
const showSentOnly = ref(true);

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

    // Ajout du filtre pour les cours envoyés
    if (showSentOnly.value) {
        filtered = filtered.filter((course) => course.is_sent);
    } else {
        filtered = filtered.filter((course) => !course.is_sent);
    }
    console.log(filtered);

    return filtered;
});

const sendForm = (courseId) => {
    const course = props.courses.find((c) => c.id === courseId);

    if (course.students.length === 0) {
        alert("Aucun étudiant inscrit à ce cours");
        return;
    }
    const endDate = new Date(course.end_date);
    const currentDate = new Date();

    if (endDate > currentDate) {
        alert(
            "Impossible d'envoyer le formulaire pour un cours qui n'est pas encore terminé, veuillez réessayer quand le cours sera terminé"
        );
        return;
    }

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

const deleteForm = useForm({});

const deleteCourse = (id) => {
    deleteForm.delete(route("courses.destroy", id));
};

const navigateToCourse = (courseId) => {
    router.visit(route("courses.show", courseId));
};

const navigateToSurvey = (course) => {
    router.visit(route("survey.details", course.survey.id));
};
const toggleSwitchIsSentCourse = () => {
    showSentOnly.value = !showSentOnly.value;
};
</script>
