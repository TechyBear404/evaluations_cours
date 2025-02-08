<script setup>
import { nextTick, ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Toaster } from "@/Components/ui/sonner";
import { toast } from "vue-sonner";
import { usePage } from "@inertiajs/vue3";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};

const page = usePage();

// Watch for flash messages from the backend
watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            nextTick(() => {
                toast.success(message);
            });
        }
    },
    { deep: true, immediate: true }
);

watch(
    () => page.props.flash?.error,
    (message) => {
        if (message) {
            nextTick(() => {
                toast.error(message);
            });
        }
    }
);

const navigationLinks = [
    {
        name: "Cours",
        route: route("courses.index"),
        active: route().current("courses.*"),
        icon: "fa-solid fa-book",
    },
    {
        name: "Professeurs",
        route: route("teachers.index"),
        active: route().current("teachers.index"),
        icon: "fa-solid fa-chalkboard-teacher",
    },
    {
        name: "Formulaires",
        route: route("form.index"),
        active: route().current("form.*"),
        icon: "fa-solid fa-clipboard-list",
    },
    {
        name: "Enquetes",
        route: route("survey.index"),
        active: route().current("survey.*"),
        icon: "fa-solid fa-clipboard-list",
    },
];
</script>

<template>
    <div>
        <Head :title="title" />
        <Banner />
        <div class="min-h-screen bg-gray-50">
            <nav
                class="fixed top-0 left-0 right-0 z-50 bg-white border-b-2 shadow-md border-primary"
            >
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="flex items-center shrink-0">
                                <Link
                                    :href="route('courses.index')"
                                    class="transition-opacity hover:opacity-80"
                                >
                                    <figure class="h-14">
                                        <img
                                            src="/images/logo.svg"
                                            alt="Logo de l'application"
                                            class="h-full object-fit"
                                        />
                                    </figure>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    v-for="(link, index) in navigationLinks"
                                    :key="index"
                                    :href="link.route"
                                    :active="link.active"
                                    class="transition-all hover:text-primary group"
                                >
                                    <font-awesome-icon
                                        :icon="link.icon"
                                        class="mr-2 transition-colors group-hover:text-primary"
                                        :class="{ 'text-primary': link.active }"
                                    />
                                    {{ link.name }}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium transition border rounded-md bg-white/50 hover:bg-primary/5 border-primary/10 hover:border-primary/20 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <font-awesome-icon
                                                    icon="fa-solid fa-chevron-down"
                                                    class="w-4 h-4 ml-2"
                                                />
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="p-1">
                                            <DropdownLink
                                                :href="route('profile.show')"
                                                class="flex items-center px-4 py-2 text-sm"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-user"
                                                    class="mr-2 text-primary"
                                                />
                                                Profile
                                            </DropdownLink>

                                            <form @submit.prevent="logout">
                                                <DropdownLink
                                                    as="button"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600"
                                                >
                                                    <font-awesome-icon
                                                        icon="fa-solid fa-right-from-bracket"
                                                        class="mr-2"
                                                    />
                                                    Déconnexion
                                                </DropdownLink>
                                            </form>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center -me-2 sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 transition rounded-md text-primary/60 hover:text-primary hover:bg-primary/5"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <font-awesome-icon
                                    :icon="
                                        showingNavigationDropdown
                                            ? 'fa-solid fa-xmark'
                                            : 'fa-solid fa-bars'
                                    "
                                    class="w-6 h-6"
                                />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            v-for="(link, index) in navigationLinks"
                            :key="index"
                            :href="link.route"
                            :active="link.active"
                            class="flex items-center"
                        >
                            <font-awesome-icon :icon="link.icon" class="mr-2" />
                            {{ link.name }}
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-primary/10">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="text-base font-medium">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink
                                :href="route('profile.show')"
                                :active="route().current('profile.show')"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-user"
                                    class="mr-2"
                                />
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')"
                                :active="route().current('api-tokens.index')"
                            >
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    <font-awesome-icon
                                        icon="fa-solid fa-right-from-bracket"
                                        class="mr-2"
                                    />
                                    Log Out
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template
                                v-if="$page.props.jetstream.hasTeamFeatures"
                            >
                                <div class="border-t border-gray-200" />

                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink
                                    :href="
                                        route(
                                            'teams.show',
                                            $page.props.auth.user.current_team
                                        )
                                    "
                                    :active="route().current('teams.show')"
                                >
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    v-if="$page.props.jetstream.canCreateTeams"
                                    :href="route('teams.create')"
                                    :active="route().current('teams.create')"
                                >
                                    Create New Team
                                </ResponsiveNavLink>

                                <!-- Team Switcher -->
                                <template
                                    v-if="
                                        $page.props.auth.user.all_teams.length >
                                        1
                                    "
                                >
                                    <div class="border-t border-gray-200" />

                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400"
                                    >
                                        Switch Teams
                                    </div>

                                    <template
                                        v-for="team in $page.props.auth.user
                                            .all_teams"
                                        :key="team.id"
                                    >
                                        <form
                                            @submit.prevent="switchToTeam(team)"
                                        >
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <svg
                                                        v-if="
                                                            team.id ==
                                                            $page.props.auth
                                                                .user
                                                                .current_team_id
                                                        "
                                                        class="text-green-400 me-2 size-5"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        />
                                                    </svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="pt-16">
                <!-- Add padding top to account for fixed nav -->
                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-white shadow">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="h-[calc(100vh-4rem)] overflow-y-auto">
                    <slot />
                </main>

                <Toaster theme="light" richColors />
            </div>
        </div>
    </div>
</template>

<style>
.router-link-active {
    @apply text-primary border-b-2 border-primary/50;
}

/* Custom Scrollbar Styles */
::-webkit-scrollbar {
    @apply w-2;
}

::-webkit-scrollbar-track {
    @apply bg-secondary rounded-full my-1;
}

::-webkit-scrollbar-thumb {
    @apply bg-primary/90 rounded-full hover:bg-primary/60 transition-colors;
}

/* Firefox */
/* * {
    scrollbar-width: thin;
    scrollbar-color: rgb(var(--primary) / 0.4) rgb(var(--secondary) / 0.1);
} */
</style>
