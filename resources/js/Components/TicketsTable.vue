<template>

    <div class="flex items-center justify-between mb-7">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Lista de tickets</h2>

            <p class="mt-1 text-sm text-gray-600">
                Los tickets mas recientes aparecen primero.
            </p>
        </header>
        <PrimaryLink :href="route('tickets.create')">
            Crear nuevo ticket
        </PrimaryLink>
    </div>

    <table class="table bordered">
        <thead>
            <tr>
                <th>Número</th>
                <th>Asunto</th>
                <th>Cuenta</th>
                <th>Status</th>
                <th>Fecha</th>
                <th class="text-right">Detalles</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="ticket in tickets" :key="ticket.id">
                <td>{{ ticket.number }}</td>
                <td>{{ ticket.subject }}</td>
                <td>{{ ticket.account_name }}</td>
                <td class="whitespace-nowrap">{{ ticket.status }}</td>
                <td class="whitespace-nowrap">{{ ticket.created_at }}</td>
                <td class="text-right">
                    <Link :href="route('tickets.show', ticket.id)">
                        Ver más
                    </Link>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>

import PrimaryLink from '@/Components/PrimaryLink.vue';
import { Link } from '@inertiajs/vue3';

export default {
    name: 'TicketsTable',
    components: {
        PrimaryLink,
        Link,
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