<template>
    <div class="card">
        <div class="w-6/12">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Contraseña</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Actualiza la contraseña del ingeniero.
                    </p>
                </header>
            </div>
            <form action="" class="space-y-6" @submit.prevent="update">
                <div class="flex items-start space-x-6">
                    <div class="w-1/2">
                        <InputLabel label="Contraseña" value="Contraseña" />
                        <TextInput v-model="agent.password" type="password" id="password" />
                        <div v-if="$page.props.errors.updateAgentSecurity" class="text-dynacom-red text-sm">
                            {{ $page.props.errors.updateAgentSecurity.password }}
                        </div>
                    </div>
                    <div class="w-1/2">
                        <InputLabel
                            label="Confirmar contraseña"
                            value="Confirmar contraseña"
                        />
                        <TextInput v-model="agent.password_confirmation" type="password" id="password_confirmation" />
                        <div v-if="$page.props.errors.updateAgentSecurity" class="text-dynacom-red text-sm">
                            {{ $page.props.errors.updateAgentSecurity.password_confirmation }}
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.flash.agentSecurityUpdated" class="alert">
                    <p class="flashMsg !m-0">
                    {{ $page.props.flash.agentSecurityUpdated }}
                    </p> 
                </div>

                <PrimaryButton type="submit">
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
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "AgentPassword",
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
        InputError,
    },
    props: {
        agent: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            submiting: false,
            errors: {},
        };
    },
    methods: {
        update() {
            let t = this;
            let route = `/dashboard/agents/${t.agent.id}/update-security`;
            let data = {
                password: t.agent.password,
                password_confirmation: t.agent.password_confirmation,
            };
            t.submiting = true;
            t.$inertia.post(route, data, {
                preserveScroll: true,
                preserveState: true,
                errorBag: "updateAgentSecurity",
                onSuccess: () => {
                    t.submiting = false;

                    // Necesitamos quitar el focus de ambos inputs

                    document.getElementById("password").blur();
                    document.getElementById("password_confirmation").blur();
                },
                onError: (errors) => {
                    t.submiting = false;
                    t.errors = errors;
                },
            });
        }
    }
};
</script>
