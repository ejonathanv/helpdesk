<template>
    <Head title="Tickets" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tickets</h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <TicketsTable :tickets="tickets" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TicketsTable from '@/Components/TicketsTable.vue';
import { Head } from '@inertiajs/vue3';

export default {
    name: 'Tickets',
    components: {
        AuthenticatedLayout,
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