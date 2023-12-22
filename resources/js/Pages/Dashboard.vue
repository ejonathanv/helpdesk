<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="container">
                <div class="flex items-stretch space-x-6">
                    <div class="w-1/2">
                        <OpenAndClosedTickets />
                    </div>
                    <div class="w-1/2">
                        <ResponseTime />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import OpenAndClosedTickets from "@/Components/OpenAndClosedTickets.vue";
import ResponseTime from "@/Components/ResponseTime.vue";

export default {
    name: "Dashboard",
    components: {
        AuthenticatedLayout,
        Head,
        OpenAndClosedTickets,
        ResponseTime,
    },
    data() {
        return {};
    },
};
</script>
