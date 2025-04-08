<template>
    <div class="main-content">
        <div class="admin">
            <center><h1>Price by admin</h1></center>

            <p>
                This price was added by an admin and does not fully reflect the actual price of the item,
                and may not be accurate. The price may be higher or lower than the actual price.
            </p>

        </div>
    </div>
</template>

<script>

export default {
    name: 'addAdmin',
}

</script>

<style scoped>
.h1,div,p,ul,li,h2{
 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.admin{
 max-width: 1100px;
 padding-top: 50px;
  margin: auto;
  color: #000000;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding: 30px;
  text-align: left;
  font-size: 1rem;
}

.main-content {
  padding-top: 0;
  margin-top: -250px;
  align-items: left;
  justify-content: left;
  display: flex;
  min-height: calc(100vh - 50px);
}

.h1 {
    text-align: center;
}

.p {
  font-size: 2rem;

}
</style>