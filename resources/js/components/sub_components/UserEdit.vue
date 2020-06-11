<!-- Shows a user with options for editing or deleting them in a row format
    VARIABLES:
      index - Number for the index of the user.
      user - Object for all user data.
      current_user - Object for the current active user
      user_types - Array of all user types.
    PARENT REQUIREMENTS:
      fetchData() - FUNCTION: updates the data on the page.
      loading - VARIABLE: Boolean showing loading status.
      users - VARIABLE: Array of all users.
-->
<template>

  <div class="row p-2" v-bind:class="index%2 ? 'bg-light' : ''">

    <div class="col col-12 col-md-3">
      {{user.name}} ({{user.email}})
      <br/>User Since: <span v-if="user.created_at_formatted" class="text-info">{{user.created_at_formatted}}</span><span v-else class="text-info">Manually Created</span>
      <br/>Last Login: <span v-if="user.last_logged_in_formatted" class="text-info">{{user.last_logged_in_formatted}}</span><span v-else class="text-danger">Never</span>
    </div>
    <div class="col col-12 col-md-3">
      User Type: {{user.user_type_id ? user.userType.name : 'NA'}}
      <br/>Admin: <span :class="user.is_admin ? 'text-success' : 'text-secondary'">{{user.is_admin ? 'Yes' : 'No'}}</span>
      <br/>Active: <span :class="user.is_active ? 'text-success' : 'text-secondary'">{{user.is_active ? 'Yes' : 'No'}}</span>
    </div>
    <div class="col col-12 col-md-6 my-auto text-right">
      <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" :data-target="'#userEditModal_'+user.id">Edit</button>
      <button v-on:click="deleteUser()" type="button" class="btn btn-sm btn-outline-danger m-1" :disabled="user.id == current_user.id">Delete</button>
    </div>

    <!-- User Edit Modal -->
    <user-edit-modal :index="index" :user="user" :current_user="current_user" :user_types="user_types"></user-edit-modal>

  </div>

</template>

<script>

  import UserEditModal from './UserEditModal';

  export default {
    components: { 'user-edit-modal': UserEditModal },
    props: {
      index: Number,
      user: Object,
      current_user: Object,
      user_types: Array
    },
    watch: {
      user: {
        handler: function() { this.updateUser() },
        deep: true
      }
    },
    methods: {

      // Deletes the user in the database, will only refresh on error.
      deleteUser() {
        this.$parent.loading = true;
        this.$delete(this.$parent.users,this.index);
        axios.delete("/users/"+this.user.id)
          .then(response => { this.$parent.loading = false; })
          .catch(error => { this.$parent.fetchData(); });
      },

      // Updates the user in the database, will only refresh on error.
      updateUser() {
        this.$parent.loading = true;
        axios.put('/users/'+this.user.id,{
          name: this.user.name,
          email: this.user.email,
          user_type_id: this.user.user_type_id,
          is_admin: this.user.is_admin,
          is_active: this.user.is_active
        })
          .then(response => { this.$parent.loading = false; })
          .catch(error => { this.$parent.fetchData(); });
      },

    }
  }

</script>
