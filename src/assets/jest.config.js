module.exports = {
    testEnvironment: 'jsdom',
    testMatch: ['**/?(*.)+spec.js'],
    moduleFileExtensions: ['ts', 'tsx', 'js', 'jsx', 'json', 'node'],
    transform: {
        '^.+\\.js$': 'babel-jest',
    },
};