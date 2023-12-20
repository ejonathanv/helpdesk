<template>
    <!-- Encabezado de la tabla -->
    <div class="flex items-center justify-between mb-7">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Lista de departamentos</h2>

            <p class="mt-1 text-sm text-gray-600">
                Aquí puedes ver la lista de departamentos.
            </p>
        </header>
        <PrimaryLink :href="route('departments.create')">
            Crear nuevo departamento
        </PrimaryLink>
    </div>

    <!-- Alerta de departamento eliminado -->
    <div v-if="$page.props.flash.departmentDeleted" class="alert">
        <p class="flashMsg">
            {{ $page.props.flash.departmentDeleted }}
        </p>
    </div>

    <!-- Si hay departamentos registrados se muestra la tabla de departamentos -->
    <div class="table-responsive" v-if="departments.length">
        <table class="table bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Total de ingenieros</th>
                    <th class="text-right">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="department in departments" :key="department.id">
                    <td>{{ department.name }}</td>
                    <td>{{ department.agents_count }}</td>
                    <td class="text-right">
                        <Link :href="route('departments.show', department.id)">
                            Ver más
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Si no hay departamentos registrados se muestra un mensaje -->
    <div v-else>
        <p class="text-gray-500 text-center my-6">
            No hay departamentos registrados.
        </p>
    </div>
</template>

<script>
import PrimaryLink from "@/Components/PrimaryLink.vue";
import { Link } from "@inertiajs/vue3";

export default {
    name: "DepartmentsTable",
    components: {
        PrimaryLink,
        Link,
    },
    props: {
        departments: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>
