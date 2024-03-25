<template>
    <Head title="Mis Tickets" />
    <GuestAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Tickets</h2>
        </template>
        <div class="container full py-7">
            <div class="card">
                <TicketsTable :tickets="tickets" type="guest" />
            </div>
        </div>
    </GuestAuthenticatedLayout>
</template>

<script>
import GuestAuthenticatedLayout from "@/Layouts/GuestAuthenticatedLayout.vue";
import TicketsTable from '@/Components/TicketsTable.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'GuestTickets',
    components: {
        GuestAuthenticatedLayout,
        Head,
        TicketsTable,
    },
    props: {
        tickets: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>