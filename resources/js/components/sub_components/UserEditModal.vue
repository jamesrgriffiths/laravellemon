<!-- Directly dependant on the UserEdit component -->
<template>
  <div class="modal fade" :id="'userEditModal_'+user.id" tabindex="-1" role="dialog" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userEditModalLabel">Edit User #{{user.id}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row mb-1">
            <div class="col col-3 my-auto h6">Name</div>
            <div class="col col-9"><input type="text" class="form-control" v-model="user.name"></div>
          </div>

          <div class="row mb-1">
            <div class="col col-3 my-auto h6">Email Address</div>
            <div class="col col-9"><input type="text" class="form-control" v-model="user.email"></div>
          </div>

          <div class="row mb-1">
            <div class="col col-3 my-auto h6">User Type</div>
            <div class="col col-9">
              <select v-model="user.user_type_id" class="form-control">
                <option value="0"></option>
                <option v-for="user_type in user_types" :value="user_type.id">{{user_type.name}}</option>
              </select>
            </div>
          </div>

          <div class="row mb-1">
            <div class="col col-3 my-auto h6">Access</div>
            <div class="col col-9 my-auto">
              <button v-on:click="user.is_admin = !user.is_admin" type="button"
                class="btn btn-sm m-1" :class="user.is_admin ? 'btn-primary' : 'btn-outline-secondary'"
                :disabled="user.id == current_user.id">Admin</button>
              <button v-on:click="user.is_active = !user.is_active" type="button"
                class="btn btn-sm m-1" :class="user.is_active ? 'btn-primary' : 'btn-outline-secondary'"
                :disabled="user.id == current_user.id">Active</button>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-success" data-dismiss="modal">Done</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      index: Number,
      user: Object,
      current_user: Object,
      user_types: Array
    },
  };
</script>
