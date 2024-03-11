<template>
    <Head title="Dashboard" />

    <GuestAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Escritorio
            </h2>
        </template>

        <div class="py-12">
            <div class="container">
            </div>
        </div>
    </GuestAuthenticatedLayout>
</template>

<script>
import GuestAuthenticatedLayout from "@/Layouts/GuestAuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    name: "GuestDashboard",
    components: {
        GuestAuthenticatedLayout,
        Head,
    },
    data() {
        return {};
    },
};
</script>
