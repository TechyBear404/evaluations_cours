<template>
    <Head title="Création d'un cours" />
    <AppLayout>
        <div class="container p-6 mx-auto">
            <Card
                class="max-w-2xl mx-auto transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
            >
                <CardHeader>
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-lg bg-primary/10">
                            <font-awesome-icon
                                icon="fa-solid fa-plus"
                                class="text-xl text-primary"
                            />
                        </div>
                        <div class="space-y-1">
                            <CardTitle class="text-2xl font-bold"
                                >Création d'un cours</CardTitle
                            >
                            <CardDescription class="text-base">
                                Ajoutez un nouveau cours au système
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nom du cours</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                :error="form.errors.name"
                                placeholder="Entrez le nom du cours"
                                class="w-full"
                            />
                            <span
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="teacher">Enseignant</Label>
                            <Select v-model="form.teacher_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue
                                        placeholder="Sélectionner un enseignant"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="teacher in teachers"
                                        :key="+teacher.id"
                                        :value="String(teacher.id)"
                                    >
                                        {{ teacher.firstname }}
                                        {{ teacher.lastname }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <span
                                v-if="form.errors.teacher_id"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.teacher_id }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="form">Formulaire</Label>
                            <Select v-model="form.form_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue
                                        placeholder="Sélectionner un formulaire"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="form in forms"
                                        :key="+form.id"
                                        :value="String(form.id)"
                                    >
                                        {{ form.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <span
                                v-if="form.errors.form_id"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.form_id }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="start_date">Date de début</Label>
                                <Input
                                    type="date"
                                    id="start_date"
                                    v-model="form.start_date"
                                    class="w-full"
                                />
                                <span
                                    v-if="form.errors.start_date"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.start_date }}
                                </span>
                            </div>

                            <div class="space-y-2">
                                <Label for="end_date">Date de fin</Label>
                                <Input
                                    type="date"
                                    id="end_date"
                                    v-model="form.end_date"
                                    class="w-full"
                                />
                                <span
                                    v-if="form.errors.end_date"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.end_date }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="emails">Liste des adresses email</Label>
                            <Textarea
                                id="emails"
                                v-model="form.emails"
                                placeholder="Entrez les adresses email (une par ligne)"
                                class="w-full min-h-[100px]"
                            />

                            <span
                                v-if="form.errors.emails"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.emails }}
                            </span>
                        </div>
                    </form>
                </CardContent>
                <CardFooter class="flex justify-between">
                    <Button variant="outline" :href="route('courses.index')">
                        <font-awesome-icon
                            icon="fa-solid fa-arrow-left"
                            class="mr-2"
                        />
                        Retour
                    </Button>
                    <Button @click="submit" :disabled="form.processing">
                        <font-awesome-icon
                            icon="fa-solid fa-save"
                            class="mr-2"
                        />
                        Créer le cours
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardFooter,
} from "@/Components/ui/card";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Textarea } from "@/Components/ui/textarea";
import { watch } from "vue";

const page = usePage();

watch(() => page.props, {
    immediate: true,
    handler: (newValue) => {
        console.log(newValue);
    },
});

const props = defineProps({
    teachers: {
        type: Array,
        required: true,
    },
    forms: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: "",
    teacher_id: null,
    form_id: null,
    year: null,
    start_date: null,
    end_date: null,
    emails: "", // Ajout du nouveau champ
});

const submit = () => {
    form.emails = form.emails
        .split("\n")
        .map((email) => email.trim())
        .filter((email) => email !== "");

    console.log(form.emails);
    form.year = form.start_date.split("-")[0];
    form.post(route("courses.Store"));
};
</script>
