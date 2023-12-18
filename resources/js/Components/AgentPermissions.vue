<template>
    <div class="card">
        <div class="w-6/12">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Permisos</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Asigna los permisos que tendrá el ingeniero.
                    </p>
                </header>
            </div>
            <form action="" class="space-y-6" @submit.prevent="submit">
                <div>
                    <InputLabel label="Permisos" value="Permisos" />
                    <select
                        name=""
                        id=""
                        class="form-control"
                        v-model="agent.permission_id"
                    >
                        <option disabled selected value="">
                            Selecciona los permisos
                        </option>
                        <option
                            v-for="permission in permissions"
                            :key="permission.id"
                            :value="permission.id"
                        >
                            {{ permission.name }}
                        </option>
                    </select>
                    <div v-if="$page.props.errors.updateAgentPermissions" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPermissions.permission_id }}
                    </div>
                </div>

                <div v-if="$page.props.flash.agentPermissionsUpdated" class="alert">
                    <p class="flashMsg !m-0">
                    {{ $page.props.flash.agentPermissionsUpdated }}
                    </p> 
                </div>

                <PrimaryButton type="submit" @click="update">
                    <span v-if="submiting" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span> Un momento... </span>
                    </span>
                    <span v-else>Actualizar</span>
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>

<script>

import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "AgentPermissions",
    components: {
        PrimaryButton,
        InputLabel,
        InputError,
    },
    props: {
        agent: {
            type: Object,
            required: true,
        },
        permissions: {
            type: Object,
            required: true,
        }
    },
    data(){
        return {
            submiting: false,
            errors: {},
        }
    },
    methods: {
        submit(){
            let t = this;
            let route = `/dashboard/agents/${t.agent.id}/update-permissions`;

            t.$inertia.post(route, t.agent, {
                preserveState: true,
                preserveScroll: true,
                errorBag: "updateAgentPermissions",
                onSuccess: (data) => {
                    t.submiting = false;
                },
                onError: (data) => {
                    t.submiting = false;
                },
            });
        }
    }

}

</script>
