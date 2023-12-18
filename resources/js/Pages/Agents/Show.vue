<template>
    <Head title="Información del ingeniero" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Información del ingeniero
            </h2>
        </template>
        <div class="container py-7 space-y-6">
            <!-- Edicion de datos personales -->
            <AgentPersonalData :agent="agent" :departments="departments" />

            <!-- Edicion de permisos -->
            <AgentPermissions :agent="agent" :permissions="permissions" />

            <!-- Seguridad / Contraseña -->
            <AgentSecurity :agent="agent" />

            <!-- Eliminar cuenta -->
            <AgentDelete :agent="agent" />
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import AgentPersonalData from "@/Components/AgentPersonalData.vue";
import AgentPermissions from "@/Components/AgentPermissions.vue";
import AgentSecurity from "@/Components/AgentSecurity.vue";
import AgentDelete from "@/Components/AgentDelete.vue";

export default {
    name: "ShowTicket",
    props: {
        agent: {
            type: Object,
            required: true,
        },
        permissions: {
            type: Object,
            required: true,
        },
        departments: {
            type: Object,
            required: true,
        },
    },
    components: {
        AuthenticatedLayout,
        Head,
        AgentPersonalData,
        AgentPermissions,
        AgentSecurity,
        AgentDelete,
    },
};
</script>
