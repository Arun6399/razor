module.exports = {
    apps: [
        {
            name: "wallets",
            script: "./server.js",
            watch: true,
            env_development: {
                "PORT": 7000,
                "NODE_ENV": "development"
            },
            env_production: {
                "PORT": 7000,
                "NODE_ENV": "production",
            }
        }
    ]
}
