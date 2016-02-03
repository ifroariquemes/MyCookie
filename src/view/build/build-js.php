({
    baseUrl: "../",
    paths: {
    <?php 
    foreach ($data as $key => $value) {
        printf("        %s: \"%s\",\n", $key, $value);
    } 
    ?>
    },
    name: "components/build-config",
    out: "bundle.js"
})