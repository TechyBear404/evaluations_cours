<template>
    <AppLayout>
        <div class="container p-6 mx-auto">
            <Card
                class="max-w-4xl mx-auto transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
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
                        <div
                            class="p-4 transition-all border rounded-lg bg-secondary/5"
                        >
                            <div class="space-y-4">
                                <!-- Teacher Selection -->
                                <div class="flex items-center gap-4">
                                    <div class="p-2 rounded-md bg-primary/10">
                                        <font-awesome-icon
                                            icon="fa-solid fa-user-tie"
                                            class="text-primary"
                                        />
                                    </div>
                                    <Select
                                        v-model="form.teacher_id"
                                        class="w-[280px]"
                                    >
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

                                <!-- Date Selection -->
                                <div class="flex items-center gap-4">
                                    <div class="p-2 rounded-md bg-primary/10">
                                        <font-awesome-icon
                                            icon="fa-solid fa-calendar"
                                            class="text-primary"
                                        />
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <Input
                                            type="date"
                                            v-model="form.start_date"
                                            class="w-[200px]"
                                        />
                                        <span class="text-muted-foreground"
                                            >à</span
                                        >
                                        <Input
                                            type="date"
                                            v-model="form.end_date"
                                            class="w-[200px]"
                                            :min="form.start_date"
                                        />
                                    </div>
                                </div>

                                <!-- Form Selection -->
                                <div class="flex items-center gap-4">
                                    <div class="p-2 rounded-md bg-primary/10">
                                        <font-awesome-icon
                                            icon="fa-solid fa-clipboard-list"
                                            class="text-primary"
                                        />
                                    </div>
                                    <Select
                                        v-model="form.form_id"
                                        class="w-[280px]"
                                    >
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
                            </div>

                            <div
                                class="p-4 transition-all border rounded-lg bg-secondary/5"
                            >
                                <div
                                    v-if="studentsEmail?.length"
                                    class="flex flex-wrap gap-2"
                                >
                                    <Badge
                                        v-for="student in studentsEmail"
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
                        </div>
                    </form>
                </CardContent>
                <CardFooter>
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center gap-2">
                            <Button
                                v-if="!course.is_sent"
                                variant="outline"
                                :disabled="!form.form_id"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-paper-plane"
                                    class="mr-2"
                                />
                                Envoyer le formulaire
                            </Button>
                            <Button v-if="course.is_sent" variant="outline">
                                <font-awesome-icon
                                    icon="fa-solid fa-file-export"
                                    class="mr-2"
                                />
                                Générer le rapport
                            </Button>
                        </div>
                        <div class="flex items-center gap-2">
                            <Button variant="default" @click="submit">
                                <font-awesome-icon
                                    icon="fa-solid fa-save"
                                    class="mr-2"
                                />
                                Sauvegarder
                            </Button>
                            <Dialog>
                                <DialogTrigger asChild>
                                    <Button variant="destructive">
                                        <font-awesome-icon
                                            icon="fa-solid fa-trash"
                                        />
                                    </Button>
                                </DialogTrigger>
                                <DialogContent>
                                    <DialogHeader>
                                        <DialogTitle
                                            >Êtes-vous sûr ?</DialogTitle
                                        >
                                        <DialogDescription>
                                            Cette action est irréversible. Le
                                            cours et toutes les données
                                            associées seront définitivement
                                            supprimés.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <DialogFooter>
                                        <DialogClose asChild>
                                            <Button variant="secondary"
                                                >Annuler</Button
                                            >
                                        </DialogClose>
                                        <Button
                                            variant="destructive"
                                            @click="deleteCourse"
                                        >
                                            Supprimer
                                        </Button>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>
                        </div>
                    </div>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
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
    form.put(route("courses.Update", props.course.id), {
        onSuccess: () => {
            form.students = temp_students;
        },
    });
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

const deleteCourse = () => {
    router.get(route("courses.destroy", { id: props.course.id }));
};
</script>
