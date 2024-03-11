<template>
    <!-- Encabezado de la tabla -->
    <div class="flex items-center justify-between mb-7">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Lista de usuarios</h2>

            <p class="mt-1 text-sm text-gray-600">
                En esta lista puedes ver todos los usuarios registrados.
            </p>
        </header>
        <PrimaryLink :href="route('agents.create')">
            Crear nuevo usuario
        </PrimaryLink>
    </div>

    <!-- Alerta de ingeniero dado de baja o eliminado -->
    <div v-if="$page.props.flash.agentDeleted" class="alert">
        <p class="flashMsg">
            {{ $page.props.flash.agentDeleted }}
        </p>
    </div>

    <!-- Si hay ingenieros registrados, se muestra la tabla de ingenieros -->
    <div class="table-responsive" v-if="agents.length">
        <table class="table bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Departamento</th>
                    <th>Permisos</th>
                    <th>Status</th>
                    <th class="text-right">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="agent in agents" :key="agent.id">
                    <td>{{ agent.user_name }}</td>
                    <td>{{ agent.user_email }}</td>
                    <td>{{ agent.department_name }}</td>
                    <td class="whitespace-nowrap">
                        {{ agent.permission_name }}
                    </td>
                    <td class="whitespace-nowrap">
                        {{ agent.status_name }}
                    </td>
                    <td class="text-right">
                        <Link :href="route('agents.show', agent.id)">
                            Ver más
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Si no hay agents registrados, se muestra un mensaje -->
    <div v-else>
        <p class="text-gray-500 text-center my-6">
            No hay ingenieros registrados.
        </p>
    </div>
</template>

<script>
import PrimaryLink from "@/Components/PrimaryLink.vue";
import { Link } from "@inertiajs/vue3";

export default {
    name: "AgentsTable",
    components: {
        PrimaryLink,
        Link,
    },
    props: {
        agents: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>
