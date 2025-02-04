<template>
    <Head title="Editer-Consulter un cours" />
    <AppLayout>
        <div class="container py-6 mx-auto">
            <form @submit.prevent="submit">
                <Card class="max-w-2xl mx-auto">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-2xl">
                            <font-awesome-icon icon="fa-solid fa-book" />
                            {{ course.name }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <font-awesome-icon
                                    icon="fa-solid fa-user-tie"
                                    class="text-gray-500"
                                />
                                <span class="font-semibold">Professeur:</span>
                                <Select v-model="form.teacher_id">
                                    <SelectTrigger class="w-[280px]">
                                        <SelectValue
                                            placeholder="Sélectionner un professeur"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem :value="null"
                                            >Non assigné</SelectItem
                                        >
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
                            </div>

                            <div class="flex items-center gap-2">
                                <font-awesome-icon
                                    icon="fa-solid fa-calendar"
                                    class="text-gray-500"
                                />
                                <span class="font-semibold">Période:</span>
                                <div class="flex items-center gap-4">
                                    <Input
                                        type="date"
                                        v-model="form.start_date"
                                        class="w-[200px]"
                                    />
                                    <span>-</span>
                                    <Input
                                        type="date"
                                        v-model="form.end_date"
                                        class="w-[200px]"
                                        :min="form.start_date"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <font-awesome-icon
                                    icon="fa-solid fa-clipboard"
                                    class="text-gray-500"
                                />
                                <span class="font-semibold">Formulaire:</span>

                                <Select v-model="form.form_id">
                                    <SelectTrigger class="w-[280px]">
                                        <SelectValue
                                            placeholder="Sélectionner un formulaire"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem :value="null"
                                            >Non assigné</SelectItem
                                        >
                                        <SelectItem
                                            v-for="form in forms"
                                            :key="form.id"
                                            :value="form.id"
                                        >
                                            {{ form.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <Separator />

                            <div class="mt-4">
                                <h3
                                    class="flex items-center gap-2 mb-2 text-lg font-semibold"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-users"
                                    />
                                    Étudiants inscrits
                                    <Dialog>
                                        <DialogTrigger as-child>
                                            <font-awesome-icon
                                                icon="fa-solid fa-plus"
                                                class="text-gray-500 hover:text-gray-700 hover:cursor-pointer"
                                            />
                                        </DialogTrigger>
                                        <DialogContent class="sm:max-w-[425px]">
                                            <DialogHeader>
                                                <DialogTitle
                                                    >Ajouter des
                                                    étudiants</DialogTitle
                                                >
                                                <DialogDescription>
                                                    Ajoutez les adresses email
                                                    des étudiants (une par
                                                    ligne)
                                                </DialogDescription>
                                            </DialogHeader>
                                            <div class="grid gap-4 py-4">
                                                <Textarea
                                                    v-model="newStudents"
                                                    placeholder="exemple@student.be, exemple2@student.be"
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
                                </h3>
                                <div class="p-4 rounded-lg shadow">
                                    <div
                                        v-if="studentsEmail?.length"
                                        class="flex flex-wrap gap-2"
                                    >
                                        <Badge
                                            v-for="student in studentsEmail"
                                            :key="student"
                                            variant="secondary"
                                            class="relative flex items-center gap-2 pl-8"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-user"
                                                class="text-gray-400"
                                            />
                                            {{ student }}
                                            <button
                                                @click.prevent="
                                                    removeStudent(student)
                                                "
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-xmark"
                                                    class="w-3 h-3"
                                                />
                                            </button>
                                        </Badge>
                                    </div>
                                    <p v-else class="text-gray-500">
                                        Aucun étudiant inscrit
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center space-x-4">
                                <Button
                                    v-if="!course.is_sent"
                                    variant="outline"
                                    class="ml-4"
                                    :disabled="course.form_id === null"
                                >
                                    envoyer le formulaire
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
                            <Button type="submit">Sauvegarder</Button>
                        </div>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps, onMounted, ref } from "vue";
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
import { Separator } from "@/Components/ui/separator";
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
    form_id: props.course.form?.id || null,
    teacher_id: props.course.teacher?.id || null,
    start_date: props.course.start_date || null,
    end_date: props.course.end_date || null,
    students: studentsEmail || [],
});

const submit = () => {
    const temp_students = studentsEmail.value;
    form.students = studentsEmail.value.join("\n");
    form.put(route("courses.Update", props.course.id));
    form.students = temp_students;
};

const removeStudent = (student) => {
    studentsEmail.value = form.students.filter((std) => std !== student);
    form.students = studentsEmail.value;
};

const newStudents = ref("");

const addStudents = () => {
    const emails = newStudents.value
        .split("\n")
        .map((email) => email.trim())
        .filter((email) => email !== "");

    studentsEmail.value = [...new Set([...studentsEmail.value, ...emails])];
    form.students = studentsEmail.value;
    // closeModal();
};
</script>
