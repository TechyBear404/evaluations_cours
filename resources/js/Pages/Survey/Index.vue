<template>
    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Enquetes
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 sm:px-20">
                        <div class="text-gray-500">
                            <ul v-if="surveys.length > 0">
                                <li
                                    v-for="survey in surveys"
                                    :key="survey.id"
                                    class="mb-4"
                                >
                                    <Link
                                        :href="
                                            route('survey.details', survey.id)
                                        "
                                        class="block p-4 transition duration-300 ease-in-out transform bg-gray-100 rounded-lg hover:bg-gray-200 hover:scale-105"
                                    >
                                        <div class="flex justify-between">
                                            <div class="font-semibold">
                                                {{ survey.course.name }}
                                            </div>

                                            <div>
                                                envoyé le
                                                {{
                                                    formatDate(
                                                        survey.start_date
                                                    )
                                                }}
                                            </div>
                                            <div>
                                                fin le
                                                {{
                                                    formatDate(survey.end_date)
                                                }}
                                            </div>
                                            <div>
                                                {{ survey.responses_count }}
                                                réponses /
                                                {{ survey.students_count }}
                                                étudiants
                                            </div>
                                        </div>
                                    </Link>
                                </li>
                            </ul>
                            <div v-else class="text-center text-gray-500">
                                Aucune enquête n'a été trouvée.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
import { watch } from "vue";
import { formatDate } from "@/lib/utils";

const props = defineProps({
    surveys: Array,
});

console.log(props.surveys);

watch(
    () => props.surveys,
    (surveys) => {
        console.log(surveys);
    }
);
</script>
