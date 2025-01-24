<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">Professeurs</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Card
                    v-for="teacher in teachers"
                    :key="teacher.id"
                    class="shadow-lg hover:shadow-xl transition-shadow"
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
                    <CardFooter class="flex justify-end">
                        <Button variant="outline" size="sm">
                            <font-awesome-icon
                                icon="fa-solid fa-eye"
                                class="mr-2"
                            />
                            Voir détails
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
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
} from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";

const props = defineProps(["teachers"]);
</script>
