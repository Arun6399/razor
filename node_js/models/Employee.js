const mongoose = require('mongoose');
const Schema   = mongoose.Schema

const employeeSchema  = new Schema({
    register_email: {
        type: String
    },
    register_password:{
        type:String
    },
    register_uname: {
        type: String
    },
    country: {
        type: String
    }
   
},{timestamp: true})

const Employee = mongoose.model('Employee',employeeSchema)
module.exports = Employee
