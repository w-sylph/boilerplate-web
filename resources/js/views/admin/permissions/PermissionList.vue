<template>
    <form-request
        :submit-url="submitUrl"
        confirm-dialog
        sync-on-success
        @load="load"
    >
        <card>
            <template v-for="(category, key) in categories">
                <div
                    :key="`permission-category-${key}`"
                    class="row mt-3"
                    :class="key < (categories.length - 1) ? 'border-bottom' : ''"
                >
                    <div class="col-sm-12">
                        <h4 class="font-weight-bold">
                            <i
                                :class="category.icon"
                                class="mr-2"
                            />
                            {{ category.name }}
                            <small>({{ category.description }})</small>
                        </h4>
                    </div>

                    <div
                        v-for="(permission, index) in category.permissions"
                        :key="`permission-${index}`"
                        class="form-group col-sm-12 mt-3"
                    >
                        <div class="custom-control custom-checkbox">
                            <input
                                :id="'permission-' + permission.id"
                                v-model="permission_ids"
                                name="permissions[]"
                                type="checkbox"
                                class="custom-control-input"
                                :value="permission.id"
                            >
                            <label
                                class="custom-control-label"
                                :for="'permission-' + permission.id"
                            >{{ permission.description }}</label>
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <action-button
                    type="submit"
                    :disabled="loading"
                    :loading="loading"
                    class="btn-warning"
                >
                    Change Permissions
                </action-button>
            </template>
        </card>

        <loader :loading="loading" />
    </form-request>
</template>

<script>
import CrudMixin from '../../../mixins/crud.js';

export default {
    name: 'permission-list',

    mixins: [ CrudMixin ],

    data: () => ({
        categories: {},
        permission_ids: []
    }),

    methods: {
        fetchSuccess(data) {
            this.categories = data.categories ? data.categories : this.categories;
            this.permission_ids = data.permission_ids ? data.permission_ids : this.permission_ids;
        }
    }
};
</script>