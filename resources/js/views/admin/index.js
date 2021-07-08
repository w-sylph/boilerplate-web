/**
 * Example Components
 */

Vue.component('sample-item-table', require('./samples/SampleItemTable.vue').default);
Vue.component('sample-item-view', require('./samples/SampleItemView.vue').default);

/**
 * Dashboard Components
 */

Vue.component('dashboard-analytics', require('./dashboards/DashboardAnalytics.vue').default);

/**
 * User & Permission Components
 */

Vue.component('admin-user-table', require('./admin-users/AdminTable.vue').default);
Vue.component('admin-user-view', require('./admin-users/AdminView.vue').default);

Vue.component('user-table', require('./users/UserTable.vue').default);
Vue.component('user-view', require('./users/UserView.vue').default);

Vue.component('role-table', require('./roles/RoleTable.vue').default);
Vue.component('role-view', require('./roles/RoleView.vue').default);

Vue.component('permission-list', require('./permissions/PermissionList.vue').default);

Vue.component('activity-log-table', require('./activity-logs/ActivityLogTable.vue').default);
Vue.component('notification-table', require('./notifications/NotificationTable.vue').default);

/**
 * Content Management Components
 */

Vue.component('page-table', require('./pages/PageTable.vue').default);
Vue.component('page-view', require('./pages/PageView.vue').default);

Vue.component('page-item-table', require('./pages/PageItemTable.vue').default);
Vue.component('page-item-view', require('./pages/PageItemView.vue').default);

Vue.component('article-table', require('./articles/ArticleTable.vue').default);
Vue.component('article-view', require('./articles/ArticleView.vue').default);