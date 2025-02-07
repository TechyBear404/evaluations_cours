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
                                    icon="fa-solid fa-chalkboard-teacher"
                                    class="text-xl text-primary"
                                />
                            </div>
                            <div class="space-y-1">
                                <CardTitle class="text-2xl font-bold"
                                    >Professeurs</CardTitle
                                >
                                <CardDescription class="text-base">
                                    Gérer les professeurs du système
                                </CardDescription>
                            </div>
                        </div>
                        <Button @click="openModal" class="gap-2">
                            <font-awesome-icon icon="fa-solid fa-plus" />
                            Ajouter un professeur
                        </Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <div
                        class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <Card
                            v-for="teacher in teachers"
                            :key="teacher.id"
                            class="transition-all border hover:shadow-md hover:border-primary"
                        >
                            <CardHeader>
                                <CardTitle
                                    class="flex items-center justify-between"
                                >
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="p-2 rounded-md bg-primary/10"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-user-tie"
                                                class="text-primary"
                                            />
                                        </div>
                                        <span
                                            >{{ teacher.firstname }}
                                            {{ teacher.lastname }}</span
                                        >
                                    </div>
                                    <div class="flex gap-2">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            @click="openEditModal(teacher)"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-pen"
                                            />
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            @click="deleteTeacher(teacher.id)"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-trash"
                                            />
                                        </Button>
                                    </div>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-envelope"
                                    />
                                    <span>{{ teacher.email }}</span>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </CardContent>
            </Card>
        </div>
        <!-- Modale pour ajouter un professeur -->
        <Dialog :open="modalCreateOpen" @update:open="setCreateModalOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Ajouter un professeur</DialogTitle>
                    <DialogDescription>
                        Remplissez les informations du nouveau professeur
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="firstname">Prénom</Label>
                        <Input id="firstname" v-model="form.firstname" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="lastname">Nom</Label>
                        <Input id="lastname" v-model="form.lastname" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" v-model="form.email" />
                    </div>
                </div>
                <DialogFooter>
                    <Button @click="closeModal" variant="secondary"
                        >Annuler</Button
                    >
                    <Button @click="addTeacher" :disabled="form.processing"
                        >Ajouter</Button
                    >
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Modale pour modifier un professeur -->
        <Dialog :open="modalEditOpen" @update:open="setEditModalOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Modifier un professeur</DialogTitle>
                    <DialogDescription>
                        Modifier les informations du professeur
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="edit-firstname">Prénom</Label>
                        <Input
                            id="edit-firstname"
                            v-model="editForm.firstname"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-lastname">Nom</Label>
                        <Input id="edit-lastname" v-model="editForm.lastname" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-email">Email</Label>
                        <Input
                            id="edit-email"
                            type="email"
                            v-model="editForm.email"
                        />
                    </div>
                </div>
                <DialogFooter>
                    <Button @click="closeEditModal" variant="secondary"
                        >Annuler</Button
                    >
                    <Button
                        @click="updateTeacher"
                        :disabled="editForm.processing"
                    >
                        Modifier
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardFooter,
    CardDescription,
} from "@/Components/ui/card";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { Button } from "@/Components/ui/button";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { router } from "@inertiajs/vue3";

const props = defineProps(["teachers"]);

const modalCreateOpen = ref(false);
const modalEditOpen = ref(false);

const form = useForm({
    firstname: "",
    lastname: "",
    email: "",
});

const editForm = useForm({
    id: "",
    firstname: "",
    lastname: "",
    email: "",
});

const setCreateModalOpen = (value) => {
    modalCreateOpen.value = value;
    if (!value) {
        form.reset();
    }
};

const setEditModalOpen = (value) => {
    modalEditOpen.value = value;
    if (!value) {
        editForm.reset();
    }
};

const openModal = () => {
    setCreateModalOpen(true);
};

const closeModal = () => {
    setCreateModalOpen(false);
};

const openEditModal = (teacher) => {
    editForm.id = teacher.id;
    editForm.firstname = teacher.firstname;
    editForm.lastname = teacher.lastname;
    editForm.email = teacher.email;
    setEditModalOpen(true);
};

const closeEditModal = () => {
    setEditModalOpen(false);
};

const addTeacher = () => {
    form.post(route("teachers.create"), {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    });
};

const updateTeacher = () => {
    editForm.put(route("teachers.update", { id: editForm.id }), {
        onSuccess: () => {
            closeEditModal();
            editForm.reset();
        },
    });
};

const deleteTeacher = (teacherId) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce professeur ?")) {
        router.get(route("teachers.delete", { id: teacherId }));
    }
};
</script>
