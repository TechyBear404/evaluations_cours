<template>
    <AppLayout title="Liste des formulaires">
        <div class="container p-6 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Liste des formulaires</h1>
                <Link :href="route('form.create')">
                    <Button>
                        <font-awesome-icon
                            icon="fa-solid fa-plus"
                            class="mr-2"
                        />
                        Créer un formulaire
                    </Button>
                </Link>
            </div>

            <div class="grid gap-4">
                <Card v-for="form in forms" :key="form.id">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>{{ form.name }}</CardTitle>
                            <div class="space-x-2">
                                <Link :href="route('form.show', form.id)">
                                    <Button variant="outline" size="sm">
                                        <font-awesome-icon
                                            icon="fa-solid fa-pen"
                                            class="mr-2"
                                        />
                                        Modifier
                                    </Button>
                                </Link>
                                <Link :href="route('form.show', form.id)">
                                    <Button variant="outline" size="sm">
                                        <font-awesome-icon
                                            icon="fa-solid fa-eye"
                                            class="mr-2"
                                        />
                                        Voir
                                    </Button>
                                </Link>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    @click.prevent="deleteForm(form)"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-trash"
                                        class="mr-2"
                                    />
                                    Supprimer
                                </Button>
                            </div>
                        </div>
                    </CardHeader>
                </Card>

                <div
                    v-if="forms.length === 0"
                    class="py-12 text-center text-gray-500"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-clipboard-list"
                        class="mb-4 text-4xl"
                    />
                    <p>Aucun formulaire créé</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Button } from "@/Components/ui/button";
import { Card, CardHeader, CardTitle } from "@/Components/ui/card";
import { Link, useForm } from "@inertiajs/vue3";

defineProps({
    forms: {
        type: Array,
        required: true,
    },
});

const deleteForm = (form) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce formulaire ?")) {
        useForm().delete(route("form.destroy", form.id));
    }
};
</script>

<style scoped></style>
