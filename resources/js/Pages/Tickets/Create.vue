<template>
    <Head title="Crear nuevo ticket" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo ticket</h2>
        </template>
        <div class="container py-7">
            <div class="card">
                <TicketForm :accounts="accounts" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TicketForm from '@/Components/TicketForm.vue';

export default {
    name: 'CreateTicket',
    props: {
        accounts: {
            type: Array,
            required: true,
        },
    },
    components: {
        AuthenticatedLayout,
        Head,
        TicketForm,
    },
    data() {
        return {};
    },
};
</script>