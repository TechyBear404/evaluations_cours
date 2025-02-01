<template>
    <Head title="Listes des professeurs" />
    <AppLayout>
        <div class="container p-6 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Professeurs</h1>
                <Button variant="default" size="sm" @click="openModal">
                    <font-awesome-icon icon="fa-solid fa-plus" class="mr-2" />
                    Ajouter un professeur
                </Button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="teacher in teachers"
                    :key="teacher.id"
                    class="transition-shadow shadow-lg hover:shadow-xl"
                >
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <font-awesome-icon
                                icon="fa-solid fa-user-tie"
                                class="text-gray-600"
                            />
                            {{ teacher.name }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <!-- Vous pouvez ajouter plus d'informations sur le professeur ici -->
                    </CardContent>
                    <CardFooter class="flex justify-end gap-2">
                        <Button variant="outline" size="sm">
                            <font-awesome-icon
                                icon="fa-solid fa-eye"
                                class="mr-2"
                            />
                            Voir détails
                        </Button>
                        <Button
                            variant="destructive"
                            size="sm"
                            @click="deleteTeacher(teacher.id)"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-trash"
                                class="mr-2"
                            />
                            Supprimer
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
    <!-- Modale pour ajouter un professeur -->
    <Dialog :open="isOpen" @update:open="setIsOpen">
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
                    <Label for="name">Nom</Label>
                    <Input id="name" v-model="form.name" />
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
import { ref } from "vue";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { router } from "@inertiajs/vue3";

const props = defineProps(["teachers"]);

const isOpen = ref(false);
const form = useForm({
    firstname: "",
    name: "",
    email: "",
});

const setIsOpen = (value) => {
    isOpen.value = value;
    if (!value) {
        form.reset();
    }
};

const openModal = () => {
    setIsOpen(true);
};

const closeModal = () => {
    setIsOpen(false);
};

const addTeacher = () => {
    form.post(route("teachers.create"), {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    });
};

const deleteTeacher = (teacherId) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce professeur ?")) {
        router.get(route("teachers.delete", { id: teacherId }));
    }
};
</script>
