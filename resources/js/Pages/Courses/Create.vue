<template>
    <Head title="Création d'un cours" />
    <AppLayout>
        <div class="container p-6 mx-auto">
            <Card
                class="max-w-4xl mx-auto transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
            >
                <CardHeader>
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-lg bg-primary/10">
                            <font-awesome-icon
                                icon="fa-solid fa-book"
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
                        <!-- Name input -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="p-2 rounded-md bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-book"
                                    class="text-primary"
                                />
                            </div>
                            <div class="flex-1">
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Nom du cours"
                                    class="w-72"
                                    :class="{
                                        'border-red-500': form.errors.name,
                                    }"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-destructive"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>
                        </div>

                        <!-- Teacher Selection -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="p-2 rounded-md bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-user-tie"
                                    class="text-primary"
                                />
                            </div>
                            <div class="flex-1">
                                <Select v-model="form.teacher_id">
                                    <SelectTrigger
                                        class="w-72"
                                        :class="{
                                            'border-red-500':
                                                form.errors.teacher_id,
                                        }"
                                    >
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
                                <p
                                    v-if="form.errors.teacher_id"
                                    class="mt-1 text-sm text-destructive"
                                >
                                    {{ form.errors.teacher_id }}
                                </p>
                            </div>
                        </div>

                        <!-- Form Selection -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="p-2 rounded-md bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-clipboard-list"
                                    class="text-primary"
                                />
                            </div>
                            <div class="flex-1">
                                <Select v-model="form.form_id">
                                    <SelectTrigger
                                        class="w-72"
                                        :class="{
                                            'border-red-500':
                                                form.errors.form_id,
                                        }"
                                    >
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
                                <p
                                    v-if="form.errors.form_id"
                                    class="mt-1 text-sm text-destructive"
                                >
                                    {{ form.errors.form_id }}
                                </p>
                            </div>
                        </div>

                        <!-- Date Selection -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="p-2 rounded-md bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-calendar"
                                    class="text-primary"
                                />
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <Input
                                        type="date"
                                        id="start_date"
                                        v-model="form.start_date"
                                        class="w-48"
                                        :class="{
                                            'border-red-500':
                                                form.errors.start_date,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.start_date"
                                        class="mt-1 text-sm text-destructive"
                                    >
                                        {{ form.errors.start_date }}
                                    </p>
                                </div>
                                <span class="text-muted-foreground">à</span>
                                <div class="flex-1">
                                    <Input
                                        type="date"
                                        id="end_date"
                                        v-model="form.end_date"
                                        class="w-48"
                                        :class="{
                                            'border-red-500':
                                                form.errors.end_date,
                                        }"
                                        :min="form.start_date"
                                    />
                                    <p
                                        v-if="form.errors.end_date"
                                        class="mt-1 text-sm text-destructive"
                                    >
                                        {{ form.errors.end_date }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Students Section - Full width -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-md bg-primary/10">
                                    <font-awesome-icon
                                        icon="fa-solid fa-users"
                                        class="text-primary"
                                    />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">
                                        Liste des étudiants
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        Entrez les adresses email des étudiants
                                        (une par ligne)
                                    </p>
                                </div>
                            </div>
                            <div
                                class="p-4 transition-all border rounded-lg bg-secondary/5"
                            >
                                <Textarea
                                    id="emails"
                                    v-model="form.emails"
                                    placeholder="example1@student.be
exemple2@studend.com"
                                    class="w-full min-h-[150px] bg-transparent"
                                    :class="{
                                        'border-red-500': form.errors.emails,
                                    }"
                                />

                                <div v-if="form.errors">
                                    <p
                                        v-for="(error, index) in form.errors"
                                        :key="index"
                                        class="mt-1 text-sm text-destructive"
                                    >
                                        <span v-if="error.includes('email')">{{
                                            error
                                        }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </CardContent>
                <CardFooter class="flex justify-end gap-2">
                    <Link
                        :href="route('courses.index')"
                        class="btn btn-outline btn-secondary"
                    >
                        <Button variant="outline"> Annuler </Button>
                    </Link>
                    <Button
                        @click="submit"
                        :disabled="form.processing"
                        class="gap-2"
                    >
                        <font-awesome-icon icon="fa-solid fa-save" />
                        Créer le cours
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
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
    emails: null,
});

const submit = () => {
    if (form.emails !== null) {
        form.emails = form.emails
            .split("\n")
            .map((email) => email.trim())
            .filter((email) => email !== "");
    }
    if (form.start_date) {
        form.year = form.start_date.split("-")[0];
    }

    form.post(route("courses.store"), {
        onError: () => {
            // Reconvertir le tableau d'emails en texte avec des sauts de ligne
            if (Array.isArray(form.emails)) {
                form.emails = form.emails.join("\n");
            }
        },
    });
};
</script>
