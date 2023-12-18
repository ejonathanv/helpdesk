<template>
    <div class="card !bg-red-200 !bg-opacity-25">
        <div class="w-6/12">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Status de cuenta</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Aquí puedes eliminar la cuenta de usuario.
                    </p>
                </header>
            </div>

            
            <form action="" @submit.prevent="suspendAccount" class="space-y-4 mb-6">
                <p class="mt-4 text-sm">
                    <span v-if="agent.status == 'active'">
                        Puedes suspender la cuenta de usuario, esto hará que el usuario no pueda acceder a la plataforma, pero no eliminará la cuenta.
                    </span>
                    <span v-else>
                        Puedes activar la cuenta de usuario, esto hará que el usuario pueda acceder a la plataforma.
                    </span>
                </p>

                <div v-if="$page.props.flash.agentSuspended" class="alert">
                    <p class="flashMsg !m-0">
                    {{ $page.props.flash.agentSuspended }}
                    </p> 
                </div>

                <PrimaryButton type="submit">
                    <span v-if="suspending" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span> Un momento... </span>
                    </span>
                    <span v-else>
                        <span v-if="agent.status == 'active'">
                            Suspender cuenta
                        </span>
                        <span v-else>
                            Activar cuenta
                        </span>
                    </span>
                </PrimaryButton>
            </form>

            <form action="" @submit.prevent="deleteAccount" class="space-y-4">
                <p class="mt-4 text-sm text-red-600">
                    ó bien, puedes eliminar la cuenta de usuario permanentemente.
                </p>

                <PrimaryButton type="submit" class="!bg-dynacom-red">
                    <span v-if="deleting" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span> Un momento... </span>
                    </span>
                    <span v-else>Eliminar cuenta</span>
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>

<script>

import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "AgentDelete",
    components: {
        PrimaryButton,
    },
    props: {
        agent: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            suspending: false,
            deleting: false,
            errors: {},
        };
    },
    methods: {
        suspendAccount(){
            const t = this;
            const route = '/dashboard/agents/' + this.agent.id + '/suspend';

            this.suspending = true;

            this.$inertia.post(route, {}, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    t.suspending = false;
                },
                onError: () => {
                    t.suspending = false;
                }
            });
        },
        deleteAccount(){
            const t = this;
            const route = '/dashboard/agents/' + this.agent.id;
            const confirm = window.confirm('¿Estás seguro de que deseas eliminar esta cuenta?');

            if(!confirm) return;
            
            this.deleting = true;

            this.$inertia.delete(route, {}, {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    t.deleting = false;
                },
                onError: () => {
                    t.deleting = false;
                }
            });

        }
    }
};
</script>
