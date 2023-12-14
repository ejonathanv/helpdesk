<template>
    <div class="modal">
        <div class="modalBody">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Actualizar categoría
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Selecciona una categoría para actualizar el ticket.
                    </p>
                </header>

                <a href="#" @click.prevent="close">
                    <i
                        class="fas fa-times fa-lg text-gray-600 hover:text-gray-700"
                    ></i>
                </a>
            </div>

            <div class="flex flex-col space-y-6">
                <div
                    v-if="$page.props.errors.updateTicketCategory"
                    class="text-dynacom-red text-sm"
                >
                    <p v-for="error in $page.props.errors.updateTicketCategory">
                        {{ error }}
                    </p>
                </div>

                <div>
                    <InputLabel for="category" value="Primera categoría" />
                    <select
                        id="category"
                        class="form-control !w-6/12"
                        v-model="category_one"
                        @change="getCategoryTwo"
                    >
                        <option disabled selected value="">
                            Selecciona una categoría
                        </option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div v-if="categories_two.length > 0">
                    <InputLabel for="category" value="Segunda categoría" />
                    <select
                        id="category"
                        class="form-control !w-6/12"
                        v-model="category_two"
                        @change="getCategoryThree"
                    >
                        <option disabled selected value="">
                            Selecciona una categoría
                        </option>
                        <option
                            v-for="category in categories_two"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div v-if="categories_three.length > 0">
                    <InputLabel for="category" value="Tercera categoría" />
                    <select
                        id="category"
                        class="form-control !w-6/12"
                        v-model="category_three"
                    >
                        <option disabled selected value="">
                            Selecciona una categoría
                        </option>
                        <option
                            v-for="category in categories_three"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-start mt-7">
                    <PrimaryButton type="submit" @click.prevent="update">
                        <span
                            v-if="submiting"
                            class="flex items-center space-x-2"
                        >
                            <i class="fas fa-spinner fa-spin"></i>
                            <span> Un momento... </span>
                        </span>
                        <span v-else>Actualizar</span>
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "TicketCategoryModal",
    components: {
        InputLabel,
        PrimaryButton,
    },
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            submiting: false,
            category_one: "",
            category_two: "",
            category_three: "",
            categories_two: [],
            categories_three: [],
        };
    },
    methods: {
        close() {
            this.$emit("close");
        },
        update() {
            let route =
                "/dashboard/tickets/" + this.ticket.id + "/update-category";
            let data = {
                category_one: this.category_one,
                category_two: this.category_two,
                category_three: this.category_three,
            };
            this.submiting = true;

            this.$inertia.put(route, data, {
                preserveScroll: true,
                errorBag: "updateTicketCategory",
                onSuccess: () => {
                    this.submiting = false;
                    this.close();
                },
                onError: () => {
                    this.submiting = false;
                },
            });
        },
        getCategoryTwo() {
            let route = "/dashboard/get-subcategories/" + this.category_one;
            this.category_two = "";
            this.category_three = "";
            this.categories_two = [];
            this.categories_three = [];
            fetch(route)
                .then((response) => response.json())
                .then((data) => {
                    this.categories_two = data;
                });
        },
        getCategoryThree() {
            let route = "/dashboard/get-subcategories/" + this.category_two;
            this.category_three = "";
            this.categories_three = [];
            fetch(route)
                .then((response) => response.json())
                .then((data) => {
                    this.categories_three = data;
                });
        },
    },
};
</script>
