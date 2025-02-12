<template>
    <Head :title="'Enquête : ' + course.name" />
    <div class="container max-w-3xl px-4 py-8 mx-auto">
        <header class="mb-10 space-y-4">
            <h1 class="text-3xl font-bold tracking-tight">
                Evaluation du cours "{{ course.name }}"
            </h1>
            <Card class="bg-muted">
                <CardHeader class="flex items-center space-x-2">
                    <font-awesome-icon
                        icon="fa-solid fa-info-circle"
                        class="text-muted-foreground"
                    />
                    <CardTitle class="text-lg"
                        >Information importante</CardTitle
                    >
                </CardHeader>
                <CardContent>
                    <p class="leading-relaxed text-muted-foreground">
                        Merci de prendre un moment pour remplir ce formulaire
                        anonyme concernant le cours que vous avez suivi. Nous ne
                        collectons ni votre adresse e-mail ni votre nom. Le
                        professeur concerné aura accès à une synthèse des
                        réponses, mais ne pourra pas consulter les
                        questionnaires individuels. Votre avis est précieux pour
                        nous.
                    </p>
                </CardContent>
            </Card>
        </header>

        <form @submit.prevent="onSubmit" class="space-y-8">
            <div class="space-y-6">
                <div
                    v-for="question in form.questions"
                    :key="question.id"
                    class="rounded-lg shadow-sm bg-card"
                >
                    <ComponentHandler
                        :question="question"
                        :number="question.order"
                        @update-answer="updateAnswers($event, question)"
                    />
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <Button type="submit" class="px-6">
                    <font-awesome-icon
                        icon="fa-solid fa-paper-plane"
                        class="mr-2"
                    />
                    Envoyer
                </Button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { Button } from "@/Components/ui/button";
import { Card, CardHeader, CardTitle, CardContent } from "@/Components/ui/card";
import ComponentHandler from "@/Components/form/ComponentHandler.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

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

// console.log(props.form.questions);

const updateAnswers = (content, question) => {
    const index = answers.value.findIndex(
        (answer) => answer.question_id === question.id
    );

    if (question.component.type === "table_radio") {
        // console.log(content, question.id);

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
    }

    // Si le contenu est un objet avec checked et name (cas du checkbox)
    else if (content && typeof content === "object" && "checked" in content) {
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
    // console.log("Form submitted");
    formulaire.post(
        route("survey.store", {
            id: formulaire.course_id,
            token: token,
        })
    );
};
</script>
