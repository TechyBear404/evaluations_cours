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
                                    icon="fa-solid fa-clipboard-list"
                                    class="text-xl text-primary"
                                />
                            </div>
                            <div class="space-y-1">
                                <CardTitle class="text-2xl font-bold">
                                    Liste des formulaires
                                </CardTitle>
                                <CardDescription class="text-base">
                                    Gérer vos formulaires d'évaluation
                                </CardDescription>
                            </div>
                        </div>
                        <Link :href="route('form.create')">
                            <Button class="gap-2">
                                <font-awesome-icon icon="fa-solid fa-plus" />
                                Créer un formulaire
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-if="forms.length > 0" class="grid gap-4">
                            <Link
                                v-for="form in forms"
                                :key="form.id"
                                :href="route('form.show', form.id)"
                                class="p-4 transition-all border rounded-lg hover:shadow-md hover:border-primary group hover:cursor-pointer"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="p-2 rounded-md bg-primary/10"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-file-lines"
                                                class="text-primary"
                                            />
                                        </div>
                                        <h3 class="font-semibold">
                                            {{ form.name }}
                                        </h3>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <!-- <Link
                                            :href="route('form.show', form.id)"
                                        >
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                class="gap-2"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-pen"
                                                />
                                                Modifier
                                            </Button>
                                        </Link> -->
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            class="gap-2 text-red-500 hover:bg-red-500 hover:text-red-100"
                                            @click.prevent="deleteForm(form)"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-trash"
                                            />
                                            Supprimer
                                        </Button>
                                    </div>
                                </div>
                            </Link>
                        </div>
                        <div
                            v-else
                            class="p-8 text-center text-muted-foreground"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-clipboard-list"
                                class="mb-2 text-3xl text-primary/50"
                            />
                            <p>Aucun formulaire créé</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardDescription,
} from "@/Components/ui/card";
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
