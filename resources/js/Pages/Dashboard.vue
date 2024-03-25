<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="container full">
                <div class="flex items-stretch space-x-6">
                    <!-- Tickets abiertos -->
                    <div class="w-1/4">
                        <Indicator title="Tickets abiertos" 
                            :value="report.open_tickets"
                            :bgcolor="'bg-blue-100'" />
                    </div>
                    <!-- Tickets en progreso -->
                    <div class="w-1/4">
                        <Indicator 
                            title="Tickets en proceso" 
                            :value="report.in_progress_tickets"
                            :bgcolor="'bg-yellow-100'" />
                    </div>
                    <!-- Tickets resueltos -->
                    <div class="w-1/4">
                        <Indicator 
                            title="Tickets resuletos" 
                            :value="report.resolved_tickets"
                            :bgcolor="'bg-green-100'" />
                    </div>
                    <!-- Tickets cerrados -->
                    <div class="w-1/4">
                        <Indicator 
                            title="Tickets cerrados" 
                            :value="report.closed_tickets"
                            :bgcolor="'bg-red-100'" />
                    </div>
                </div>

                <div class="flex space-x-6 items-stretch mt-6">
                    <div class="w-1/2">
                        <div class="card h-full">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                Tiempo promedio de resolución de tickets
                            </h3>
                            <h4 class="text-3xl font-bold text-gray-800">
                                12 horas
                            </h4>
                        </div>
                    </div>
                    <div class="w-1/2">
                        <div class="card h-full"></div>
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
import Indicator from "@/Components/Indicator.vue";

export default {
    name: "Dashboard",
    props: {
        report: {
            type: Object,
            required: true,
        },
    },
    components: {
        AuthenticatedLayout,
        Head,
        OpenAndClosedTickets,
        ResponseTime,
        Indicator,
    },
    data() {
        return {};
    },
};
</script>
