<template>
    <AppLayout>
        <div class="container p-6 mx-auto" @click="clearEditMode">
            <div class="grid grid-cols-12 gap-6">
                <Card
                    class="col-span-12 transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
                    @click.stop
                >
                    <CardHeader>
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-lg bg-primary/10">
                                <font-awesome-icon
                                    icon="fa-solid fa-pen-to-square"
                                    class="text-xl text-primary"
                                />
                            </div>
                            <div class="space-y-1">
                                <CardTitle class="text-2xl font-bold">
                                    Créer un nouveau formulaire
                                </CardTitle>
                                <CardDescription class="text-base">
                                    Créez un nouveau formulaire d'évaluation
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div>
                                <Label for="name">Titre du formulaire</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Entrez le titre du formulaire"
                                    class="mt-1"
                                />
                            </div>
                            <div>
                                <Label for="title">Entète du formulaire</Label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Entrez l'entête du formulaire"
                                    class="mt-1"
                                />
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <!-- Components Panel -->
                <div
                    :class="[
                        'transition-all duration-300 sticky h-[calc(100vh-7rem)] top-4',
                        isPanelCollapsed
                            ? 'col-span-1 w-24'
                            : 'col-span-3 min-w-[280px]',
                    ]"
                    @click.stop
                >
                    <Card
                        class="overflow-y-auto transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
                    >
                        <CardHeader :class="isPanelCollapsed ? 'px-2' : ''">
                            <CardTitle
                                class="flex items-center justify-between"
                                :class="isPanelCollapsed ? 'gap-0' : 'gap-1'"
                            >
                                <div class="p-3 rounded-md bg-primary/10">
                                    <font-awesome-icon
                                        icon="fa-solid fa-puzzle-piece"
                                        class="flex items-center text-2xl text-primary"
                                    />
                                </div>
                                <span v-if="!isPanelCollapsed" class="ml-2">
                                    Composants
                                </span>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    @click="togglePanel"
                                    class="hover:bg-primary/10"
                                    :class="isPanelCollapsed ? 'px-0' : 'px-1'"
                                >
                                    <font-awesome-icon
                                        :icon="
                                            isPanelCollapsed
                                                ? 'fa-solid fa-chevron-right'
                                                : 'fa-solid fa-chevron-left'
                                        "
                                        class="text-primary"
                                    />
                                </Button>
                            </CardTitle>
                        </CardHeader>
                        <CardContent :class="isPanelCollapsed ? 'p-2' : ''">
                            <draggable
                                :list="components"
                                :group="{
                                    name: 'components',
                                    pull: 'clone',
                                    put: false,
                                }"
                                item-key="id"
                                :clone="cloneComponent"
                                class="space-y-3"
                            >
                                <template #item="{ element }">
                                    <div
                                        class="p-3 transition-all border rounded-md cursor-pointer bg-secondary/10 hover:bg-secondary/20 hover:border-primary group"
                                        @click="addComponentToEnd(element)"
                                    >
                                        <div
                                            class="flex items-center"
                                            :class="{
                                                'justify-center':
                                                    isPanelCollapsed,
                                            }"
                                        >
                                            <div
                                                class="p-1.5 rounded bg-white/50 group-hover:bg-white"
                                            >
                                                <font-awesome-icon
                                                    :icon="
                                                        getComponentIcon(
                                                            element.type
                                                        )
                                                    "
                                                    class="text-primary"
                                                />
                                            </div>
                                            <span
                                                v-if="!isPanelCollapsed"
                                                class="ml-3 font-medium"
                                            >
                                                {{ element.name }}
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </draggable>
                        </CardContent>
                    </Card>
                </div>

                <!-- Builder Area -->
                <div
                    :class="[
                        'transition-all duration-300',
                        isPanelCollapsed ? 'col-span-11' : 'col-span-9',
                    ]"
                    @click.stop
                >
                    <Card
                        class="transition-shadow border-l-4 shadow-lg border-primary hover:shadow-xl"
                    >
                        <CardHeader>
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-lg bg-primary/10">
                                    <font-awesome-icon
                                        icon="fa-solid fa-layer-group"
                                        class="text-xl text-primary"
                                    />
                                </div>
                                <div class="space-y-1">
                                    <CardTitle class="text-2xl font-bold">
                                        Questions du formulaire
                                    </CardTitle>
                                    <CardDescription class="text-base">
                                        Glissez et déposez les composants pour
                                        construire votre formulaire
                                    </CardDescription>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <draggable
                                    v-model="formComponents"
                                    group="components"
                                    handle=".drag-handle"
                                    class="space-y-4"
                                    item-key="id"
                                >
                                    <template #item="{ element, index }">
                                        <div
                                            class="p-4 transition-all border rounded-lg shadow-sm bg-secondary/5 hover:shadow-md hover:border-primary hover:cursor-pointer"
                                            :class="{
                                                'border-primary':
                                                    editingComponentId ===
                                                    element.id,
                                            }"
                                        >
                                            <component
                                                :is="
                                                    getComponentType(
                                                        element.type
                                                    )
                                                "
                                                :element="element"
                                                :index="index + 1"
                                                :onDelete="handleDelete"
                                                :isEditing="
                                                    editingComponentId ===
                                                    element.id
                                                "
                                                @select="setEditMode"
                                                @addOption="
                                                    (element) =>
                                                        addOption(element)
                                                "
                                                @removeOption="
                                                    (element, index) =>
                                                        removeOption(
                                                            element,
                                                            index
                                                        )
                                                "
                                                @onAddColumn="
                                                    (element) =>
                                                        addTableColumn(element)
                                                "
                                                @onRemoveColumn="
                                                    (element, index) =>
                                                        removeTableColumn(
                                                            element,
                                                            index
                                                        )
                                                "
                                                @onAddRow="
                                                    (element) =>
                                                        addTableRow(element)
                                                "
                                                @onRemoveRow="
                                                    (element, index) =>
                                                        removeTableRow(
                                                            element,
                                                            index
                                                        )
                                                "
                                            />
                                        </div>
                                    </template>
                                    <template
                                        #footer
                                        v-if="formComponents.length === 0"
                                    >
                                        <div
                                            class="flex flex-col items-center justify-center py-12 border-2 border-dashed rounded-lg border-primary/20"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-arrow-down"
                                                class="mb-3 text-3xl text-primary/40"
                                            />
                                            <p
                                                class="text-lg text-muted-foreground"
                                            >
                                                Glissez et déposez les
                                                composants ici
                                            </p>
                                        </div>
                                    </template>
                                </draggable>
                            </div>

                            <div class="flex justify-end mt-6 space-x-2">
                                <Link :href="route('form.index')">
                                    <Button variant="outline">
                                        <font-awesome-icon
                                            icon="fa-solid fa-times"
                                            class="mr-2"
                                        />
                                        Annuler
                                    </Button>
                                </Link>
                                <Button
                                    @click="handleSave"
                                    :disabled="form.processing"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-save"
                                        class="mr-2"
                                    />
                                    Enregistrer
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import draggable from "vuedraggable";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/Components/ui/card";
import ShortInput from "@/Components/form/ShortInput.vue";
import LongInput from "@/Components/form/LongInput.vue";
import SingleChoice from "@/Components/form/SingleChoice.vue";
import MultipleChoice from "@/Components/form/MultipleChoice.vue";
import TableChoice from "@/Components/form/TableChoice.vue";

const props = defineProps({
    components: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: "",
    description: "",
    components: [],
});

const formComponents = ref([]);
const editingComponentId = ref(null);

const cloneComponent = (item) => ({
    ...item,
    sourceId: item.id,
    id: `temp-${Date.now()}`,

    question: "",
    options: item.type === "radio" || item.type === "checkbox" ? ["", ""] : [],
    tableData:
        item.type === "table_radio" ? { columns: ["", ""], rows: [""] } : null,
});

const setEditMode = (id, event) => {
    if (event) {
        event.stopPropagation();
    }
    editingComponentId.value = id;
};

const clearEditMode = () => {
    editingComponentId.value = null;
};

const getComponentType = (type) => {
    const componentMap = {
        input: ShortInput,
        textarea: LongInput,
        radio: SingleChoice,
        checkbox: MultipleChoice,
        table_radio: TableChoice,
    };
    return componentMap[type];
};

// Add new methods for managing options
const addOption = (element) => {
    if (!element.options) {
        element.options = [];
    }
    element.options.push("");
};

const removeOption = (element, index) => {
    element.options.splice(index, 1);
};

// Add methods for table management
const addTableColumn = (element) => {
    element.tableData.columns.push("");
};

const removeTableColumn = (element, index) => {
    element.tableData.columns.splice(index, 1);
};

const addTableRow = (element) => {
    element.tableData.rows.push("");
};

const removeTableRow = (element, index) => {
    element.tableData.rows.splice(index, 1);
};

const handleDelete = (id) => {
    formComponents.value = formComponents.value.filter((c) => c.id !== id);
};

const handleSave = () => {
    // Validation
    if (!isFormValid()) return;

    // Mise à jour du formulaire avec les données actuelles
    form.components = prepareComponentsForSave();
    form.post(route("form.store"));
};

const isFormValid = () => {
    if (!form.name.trim()) {
        alert("Le titre du formulaire est requis");
        return false;
    }

    if (formComponents.value.length === 0) {
        alert("Ajoutez au moins une question au formulaire");
        return false;
    }

    const hasInvalidQuestions = formComponents.value.some(
        (component) => !component.question?.trim()
    );
    if (hasInvalidQuestions) {
        alert("Toutes les questions doivent avoir un libellé");
        return false;
    }

    const hasInvalidOptions = formComponents.value.some((component) => {
        if (["radio", "checkbox"].includes(component.type)) {
            return (
                !component.options ||
                component.options.filter(Boolean).length < 2
            );
        }
        if (component.type === "table_radio") {
            return (
                !component.tableData?.columns.filter(Boolean).length ||
                !component.tableData?.rows.filter(Boolean).length
            );
        }
        return false;
    });

    if (hasInvalidOptions) {
        alert(
            "Les questions à choix doivent avoir au moins deux options valides"
        );
        return false;
    }

    return true;
};

const prepareComponentsForSave = () => {
    return formComponents.value.map((component) => ({
        ...component,
        sourceId: component.sourceId,
        options: component.options?.filter(Boolean),
        tableData: component.tableData
            ? {
                  columns: component.tableData.columns.filter(Boolean),
                  rows: component.tableData.rows.filter(Boolean),
              }
            : undefined,
    }));
};

const isPanelCollapsed = ref(false);

const togglePanel = () => {
    isPanelCollapsed.value = !isPanelCollapsed.value;
};

const getComponentIcon = (type) => {
    const iconMap = {
        input: "fa-solid fa-font",
        textarea: "fa-solid fa-align-left",
        radio: "fa-solid fa-circle-dot",
        checkbox: "fa-solid fa-square-check",
        table_radio: "fa-solid fa-table",
    };
    return iconMap[type] || "fa-solid fa-puzzle-piece";
};

const addComponentToEnd = (item) => {
    const clonedComponent = cloneComponent(item);
    formComponents.value.push(clonedComponent);
    editingComponentId.value = clonedComponent.id;
};
</script>

<style scoped>
.drag-handle {
    touch-action: none;
}

[data-dragging="true"] {
    cursor: grabbing !important;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
