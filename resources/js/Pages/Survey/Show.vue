<template>
    <Head :title="'Enquête : ' + course.name" />
    <div class="bg-blue-50">
        <div class="container max-w-5xl p-6 mx-auto">
            <div class="space-y-8">
                <!-- Header Card -->
                <Card
                    class="transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
                >
                    <CardHeader>
                        <div class="flex flex-col items-center gap-2">
                            <figure class="h-20">
                                <img
                                    src="/images/logo.svg"
                                    alt="Logo de l'application"
                                    class="h-full object-fit"
                                />
                            </figure>
                            <p class="text-4xl font-bold text-primary">
                                IfoForm
                            </p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-lg bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-clipboard-question"
                                    class="text-xl text-primary"
                                />
                            </div>
                            <h1 class="space-y-1">
                                <CardTitle
                                    class="text-2xl font-bold capitalize"
                                >
                                    {{ course.name }}
                                </CardTitle>
                                <CardDescription class="text-base">
                                    Formulaire d'évaluation du cours
                                </CardDescription>
                            </h1>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 mt-4 md:grid-cols-1">
                            <div
                                class="p-4 transition-colors rounded-lg bg-primary/10 hover:bg-primary/20"
                            >
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="p-2 rounded-md bg-primary">
                                        <font-awesome-icon
                                            icon="fa-solid fa-info-circle"
                                            class="text-primary-foreground"
                                        />
                                    </div>
                                    <h3 class="font-semibold">
                                        Information importante
                                    </h3>
                                </div>
                                <p
                                    class="mt-2 ml-4 text-sm leading-relaxed text-muted-foreground"
                                >
                                    Merci de prendre un moment pour remplir ce
                                    formulaire anonyme concernant le cours que
                                    vous avez suivi. Nous ne collectons ni votre
                                    adresse e-mail ni votre nom. Le professeur
                                    concerné aura accès à une synthèse des
                                    réponses, mais ne pourra pas consulter les
                                    questionnaires individuels. Votre avis est
                                    précieux pour nous.
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Questions Form -->
                <form @submit.prevent="onSubmit" class="space-y-6">
                    <Card
                        v-for="(question, index) in form.questions"
                        :key="question.id"
                        class="transition-shadow border-l-4 shadow-md border-primary hover:shadow-xl"
                    >
                        <CardHeader class="pb-0 pl-0">
                            <div class="flex items-center gap-4">
                                <div
                                    class="p-3 pl-6 rounded-tr-lg rounded-br-lg bg-primary text-muted"
                                >
                                    <p class="text-xl font bold">
                                        {{ index + 1 }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <CardTitle
                                        class="text-2xl font-semibold first-letter:uppercase"
                                    >
                                        {{ question.label }}
                                    </CardTitle>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent class="p-6">
                            <ComponentHandler
                                :question="question"
                                :number="question.order"
                                @update-answer="updateAnswers($event, question)"
                            />
                        </CardContent>
                    </Card>

                    <div class="flex justify-end pt-6">
                        <Button
                            type="submit"
                            class="px-6 py-2 text-white bg-primary hover:bg-primary/90"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-paper-plane"
                                class="mr-2"
                            />
                            Envoyer l'évaluation
                        </Button>
                    </div>
                </form>
            </div>
        </div>
        <Footer />
    </div>
</template>

<script setup>
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardDescription,
} from "@/Components/ui/card";
import ComponentHandler from "@/Components/form/ComponentHandler.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Footer from "@/Components/Footer.vue";

const props = defineProps({
    form: Object,
    course: Object,
});

const answers = ref(
    props.form.questions.map((question) => ({
        question_id: question.id,
        type: question.component.type,
        content: "",
    }))
);

const updateAnswers = (content, question) => {
    console.log(content, question);
    const index = answers.value.findIndex(
        (answer) => answer.question_id === question.id
    );

    if (question.component.type === "table_radio") {
        if (!Array.isArray(answers.value[index].content)) {
            answers.value[index].content = [];
        }

        let changed = false;
        answers.value[index].content.forEach((element) => {
            if (element.option_id === content.option_id) {
                element.response = content.response;
                changed = true;
            }
        });
        if (!changed) {
            answers.value[index].content = [
                ...answers.value[index].content,
                content,
            ];
            changed = false;
        }
    } else if (content && typeof content === "object" && "checked" in content) {
        if (content.checked) {
            answers.value[index].content = [
                ...(answers.value[index].content || []),
                content.name,
            ];
        } else {
            answers.value[index].content = answers.value[index].content.filter(
                (item) => item !== content.name
            );
        }
    } else {
        answers.value[index].content = content;
    }

    formulaire.answers = answers.value;
};

const formulaire = useForm({
    course_id: props.course.id,
    answers: answers.value,
});

const token = window.location.href.split("/").pop();

const onSubmit = () => {
    formulaire.post(
        route("survey.store", {
            id: formulaire.course_id,
            token: token,
        })
    );
};
</script>
