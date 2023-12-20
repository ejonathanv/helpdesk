<template>
    <div class="card !bg-red-200 !bg-opacity-25">
        <div class="w-6/12">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Eliminar departamento</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Aquí puedes eliminar un departamento.
                    </p>
                </header>
            </div>
            <form action="" class="space-y-6" @submit.prevent="deleteDepartment">
                <PrimaryButton type="submit" class="!bg-dynacom-red">
                    <span v-if="submiting" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span> Un momento... </span>
                    </span>
                    <span v-else>Eliminar</span>
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>

<script>

import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "DeleteDepartment",
    components: {
        PrimaryButton,
    },
    props: {
        department: {
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
        deleteDepartment() {
            let t = this;
            let route = '/dashboard/config/departments/' + this.department.id;
            let confirm = window.confirm(
                "¿Estás seguro de que quieres actualizar este departamento?"
            );

            if (!confirm) {
                return;
            }

            this.submiting = true;

            t.$inertia
                .delete(route, this.department)
                .then(() => {
                    t.submiting = false;
                })
                .catch((errors) => {
                    t.submiting = false;
                    t.errors = errors.response.data.errors;
                });
        }
    }
};
</script>
