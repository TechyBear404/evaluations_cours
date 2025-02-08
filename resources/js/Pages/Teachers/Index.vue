<template>
    <Head title="Listes des professeurs" />
    <AppLayout>
        <div class="container p-6 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Professeurs</h1>
                <div class="flex items-center gap-4">
                    <div class="w-64">
                        <Input
                            type="search"
                            placeholder="Rechercher un professeur..."
                            v-model="searchQuery"
                        >
                            <template #prefix>
                                <font-awesome-icon
                                    icon="fa-solid fa-search"
                                    class="text-gray-400"
                                />
                            </template>
                        </Input>
                    </div>
                    <Button variant="default" size="sm" @click="openModal">
                        <font-awesome-icon
                            icon="fa-solid fa-plus"
                            class="mr-2"
                        />
                        Ajouter un professeur
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="teacher in filteredTeachers"
                    :key="teacher.id"
                    class="transition-shadow shadow-lg hover:shadow-xl"
                >
                    <CardHeader>
                        <CardTitle
                            class="flex items-center justify-between gap-2"
                        >
                            <div class="flex items-center gap-2">
                                <font-awesome-icon
                                    icon="fa-solid fa-user-tie"
                                    class="text-gray-600"
                                />
                                <div>
                                    {{ teacher.firstname }}
                                    {{ teacher.lastname }}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="openEditModal(teacher)"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-edit"
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
                    <CardContent class="flex items-center gap-2">
                        <font-awesome-icon
                            icon="fa-solid fa-envelope"
                            class="text-gray-600"
                        />
                        <p class="text-sm text-gray-600">{{ teacher.email }}</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
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
                <Button @click="closeModal" variant="secondary">Annuler</Button>
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
                    <Input id="edit-firstname" v-model="editForm.firstname" />
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
                <Button @click="updateTeacher" :disabled="editForm.processing">
                    Modifier
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardFooter,
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
import { ref, computed } from "vue";
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

const searchQuery = ref("");

const filteredTeachers = computed(() => {
    const query = searchQuery.value.toLowerCase();
    if (!query) return props.teachers;

    return props.teachers.filter(
        (teacher) =>
            teacher.firstname.toLowerCase().includes(query) ||
            teacher.lastname.toLowerCase().includes(query) ||
            teacher.email.toLowerCase().includes(query)
    );
});
</script>
