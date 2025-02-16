<template>
    <Head :title="'Modification du cours : ' + course.name" />
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
                            <CardTitle class="text-2xl font-bold">{{
                                course.name
                            }}</CardTitle>
                            <CardDescription class="text-base">
                                Gérer les détails et les étudiants du cours
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
                                    v-model="form.name"
                                    type="text"
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
                                <Select v-model="form.teacher_id" class="w-72">
                                    <SelectTrigger
                                        class="w-72"
                                        :class="{
                                            'border-red-500':
                                                form.errors.teacher_id,
                                        }"
                                    >
                                        <SelectValue
                                            placeholder="Sélectionner un professeur"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="teacher in teachers"
                                            :key="+teacher.id"
                                            :value="teacher.id"
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
                                <Select v-model="form.form_id" class="w-72">
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
                                        <!-- <SelectItem :value="null"
                                        >Aucun formulaire</SelectItem
                                    > -->
                                        <SelectItem
                                            v-for="form in forms"
                                            :key="form.id"
                                            :value="form.id"
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

                        <!-- Students Section -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-md bg-primary/10">
                                        <font-awesome-icon
                                            icon="fa-solid fa-users"
                                            class="text-primary"
                                        />
                                    </div>
                                    <h3 class="text-lg font-semibold">
                                        Étudiants inscrits
                                    </h3>
                                </div>
                                <Dialog>
                                    <DialogTrigger asChild>
                                        <Button variant="outline" class="gap-2">
                                            <font-awesome-icon
                                                icon="fa-solid fa-plus"
                                            />
                                            Ajouter des étudiants
                                        </Button>
                                    </DialogTrigger>
                                    <DialogContent>
                                        <DialogHeader>
                                            <DialogTitle
                                                >Ajouter des
                                                étudiants</DialogTitle
                                            >
                                            <DialogDescription>
                                                Ajoutez les adresses email des
                                                étudiants (une par ligne)
                                            </DialogDescription>
                                        </DialogHeader>
                                        <div class="grid gap-4 py-4">
                                            <Textarea
                                                v-model="newStudents"
                                                placeholder="exemple@student.be
exemple2@student.be"
                                                class="min-h-[200px]"
                                            />
                                        </div>
                                        <DialogFooter>
                                            <DialogClose as-child>
                                                <Button variant="secondary"
                                                    >Annuler</Button
                                                >
                                            </DialogClose>
                                            <DialogClose>
                                                <Button @click="addStudents"
                                                    >Ajouter</Button
                                                >
                                            </DialogClose>
                                        </DialogFooter>
                                    </DialogContent>
                                </Dialog>
                            </div>

                            <div
                                class="p-4 transition-all border rounded-lg bg-secondary/5"
                            >
                                <div
                                    v-if="form.emails?.length"
                                    class="flex flex-wrap gap-2"
                                >
                                    <Badge
                                        v-for="student in form.emails"
                                        :key="student"
                                        variant="secondary"
                                        class="flex items-center gap-2 pl-3 pr-2 group"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-user"
                                            class="text-primary"
                                        />
                                        {{ student }}
                                        <button
                                            @click.prevent="
                                                removeStudent(student)
                                            "
                                            class="p-1 transition-colors rounded-full hover:bg-primary/10"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-xmark"
                                                class="w-3 h-3"
                                            />
                                        </button>
                                    </Badge>
                                </div>
                                <p v-else class="text-muted-foreground">
                                    Aucun étudiant inscrit
                                </p>
                            </div>
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
                    </form>
                </CardContent>
                <CardFooter class="flex justify-end gap-2">
                    <Link
                        :href="route('courses.index')"
                        class="btn btn-outline btn-secondary"
                    >
                        <Button variant="outline"> Annuler </Button>
                    </Link>
                    <Button variant="default" @click="submit">
                        <font-awesome-icon
                            icon="fa-solid fa-save"
                            class="mr-2"
                        />
                        Sauvegarder
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
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
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Textarea } from "@/Components/ui/textarea";
import DialogClose from "@/Components/ui/dialog/DialogClose.vue";

const props = defineProps({
    course: {
        type: Object,
    },
    teachers: {
        type: Array,
        default: () => [],
    },
    forms: {
        type: Array,
        default: () => [],
    },
});

const studentsEmail = ref(
    props.course.students.map((student) => student.email)
);

const form = useForm({
    name: props.course.name || null,
    form_id: props.course.form?.id || null,
    teacher_id: props.course.teacher?.id || null,
    start_date: props.course.start_date || null,
    end_date: props.course.end_date || null,
    emails: studentsEmail.value || [],
    year: props.course.start_date.split("-")[0],
});

// watch(
//     () => form.errors,
//     (errors) => {
//         console.log("Erreurs de validation :", errors);
//     },
//     { deep: true }
// );

const submit = () => {
    const temp_students = studentsEmail.value;

    form.put(route("courses.update", props.course.id), {
        onSuccess: () => {
            form.students = temp_students;
        },
    });
};

const removeStudent = (student) => {
    studentsEmail.value = form.emails.filter((std) => std !== student);
    console.log(studentsEmail.value);
    form.emails = studentsEmail.value;
    console.log(form.emails);
};

const newStudents = ref("");

const addStudents = () => {
    let emails = newStudents.value
        .split("\n")
        .map((email) => email.trim())
        .filter((email) => email !== "");

    emails = emails.filter((email) => !form.emails.includes(email));

    if (form.emails.length === 0) {
        form.emails = [...emails];
        return;
    }

    form.emails = [...new Set([...form.emails, ...emails])];

    newStudents.value = "";
};
</script>
