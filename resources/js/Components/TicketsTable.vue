<template>
    <!-- Encabezado de la tabla -->
    <div class="flex items-center mb-7">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Lista de tickets</h2>
            <p class="mt-1 text-sm text-gray-600">
                Los tickets mas recientes aparecen primero.
            </p>
        </header>
        <div class="ml-auto space-x-4 flex items-stretch">
            <!-- Busqueda de tickets -->
            <form :action="searchRoute()" method="GET"
                class="flex items-center space-x-3 relative">
                <input type="text" 
                    class="form-control"
                    name="search" 
                    placeholder="Buscar ticket..." />
                <button class="absolute right-3 top-2 m-0 z-10 cursor-pointer"
                    type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <!-- Botón para crear nuevo ticket -->
            <PrimaryLink :href="route('tickets.create')" v-if="$page.props.auth.user">
                Crear nuevo ticket
            </PrimaryLink>
            <PrimaryLink :href="route('guest.new-ticket')" v-else>
                Crear nuevo ticket
            </PrimaryLink>
        </div>
    </div>
    <!-- Alerta de ticket archivado o eliminado -->
    <div v-if="$page.props.flash.ticketArchived" class="alert">
        <p class="flashMsg">
            {{ $page.props.flash.ticketArchived }}
        </p>
    </div>
    <!-- Si hay tickets registrados, se muestra la tabla de tickets -->
    <div class="table-responsive" v-if="tickets.data.length">
        <table class="table bordered">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Asunto</th>
                    <th>Cuenta</th>
                    <th>Ingeniero</th>
                    <th v-if="$page.props.auth.user">
                        Departamento
                    </th>
                    <th>Status</th>
                    <th>Fecha</th>
                    <th class="text-right">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ticket in tickets.data" :key="ticket.id">
                    <td>{{ ticket.number }}</td>
                    <td>{{ ticket.subject }}</td>
                    <td>{{ ticket.account_name }}</td>
                    <td>{{ ticket.agent_name }}</td>
                    <td v-if="$page.props.auth.user">
                        {{ ticket.department_name }}
                    </td>
                    <td class="whitespace-nowrap">{{ ticket.status }}</td>
                    <td class="whitespace-nowrap">{{ ticket.created_at }}</td>
                    <td class="text-right">
                        <Link :href="route('tickets.show', ticket.id)" v-if="type === 'internal'">
                            Ver más
                        </Link>
                        <Link :href="route('guest.ticket', ticket.id)" v-else>
                            Ver más
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-5">
            <Pagination :links="tickets.links" />
        </div>
    </div>
    <!-- Si no hay tickets registrados, se muestra un mensaje -->
    <div v-else>
        <p class="text-gray-500 text-center my-6">
            No hay tickets registrados.
        </p>
    </div>
</template>
<script>
import Pagination from "@/Components/Pagination.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import { Link } from "@inertiajs/vue3";
export default {
    name: "TicketsTable",
    components: {
        PrimaryLink,
        Link,
        Pagination,
    },
    props: {
        type: {
            type: String,
            default: "internal"
        },
        tickets: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
        };
    },
    methods: {
        searchRoute() {
            return this.type === "internal" ? route("tickets.index") : route("guest.tickets");
        }
    }
};
</script>
