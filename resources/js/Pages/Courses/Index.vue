<script setup>
import Button from "@/Components/ui/button/Button.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Card, CardContent } from "@/components/ui/card";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    courses: {
        type: Array,
    },
});
</script>
<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">
                <div class="flex items-center">
                    Liste des cours
                    <Link :href="`/courses/create`">
                        <Button variant="primary">
                            <font-awesome-icon
                                icon="fa-solid fa-plus"
                                class="mr-2"
                            />
                        </Button>
                    </Link>
                </div>
            </h1>

            <div class="space-y-4">
                <Link
                    v-for="course in props.courses"
                    :key="course.id"
                    :href="`/courses/${course.id}`"
                    class="block hover:no-underline"
                >
                    <Card class="hover:bg-gray-50 transition-colors">
                        <CardContent
                            class="flex items-center justify-between py-4"
                        >
                            <div class="flex items-center space-x-4">
                                <font-awesome-icon
                                    icon="fa-solid fa-book"
                                    class="text-gray-500 text-xl"
                                />
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ course.name }}
                                </h3>
                            </div>
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
                        </CardContent>
                    </Card>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
