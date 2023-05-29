const { response } = require('express')
const Employee = require('../models/Employee')
const mongoose = require('mongoose')

const Countries = require('../models/Countries')
const Users = require('../models/users')
const Currency = require('../models/currency')

const Schema = mongoose.Schema

const index = (req,res,next) => {
    Employee.find()
    .then(response =>{
        res.json({
            response
        })
    })
    .catch(error =>{
        res.json({
            message:'An Error Occured'
        })
    })
}
    //show single employee

    // const indexs = (req,res,next) => {
    //     Countries.find()
    //     .then(response =>{
    //         res.json({
    //             response
    //         })
    //     })
    //     .catch(error =>{
    //         res.json({
    //             message:'An Error Occured'
    //         })
    //     })
    // }



const show = (req, res, next) => {
    let employeeID = req.body.employeeID
    Employee.find(employeeID)
        .then(response => {
            res.json({
                response
            })
        })
        .catch(error => {
            res.json({
                message: 'An error occured'
            })
        })
}


const shows = (req, res, next) => {
    
    // Countries.find({},{id:1,currencycode:1,currency:1}).limit(12)
    Countries.find({})

        .then(response => {
            res.json({
                response
 
                // Data.find({}).project({ _id : 1, serialno : 1 }).toArray()
            })
            var ak = response;
            console.log(ak);
        })
        .catch(error => {
            res.json({
                message: 'An error occured'
            })
        }) 

}
    //Login

    const new_find = (req, res, next) => {
        Users.find().sort({id:-1})
            .then(response => {
                res.json({
                    response
                })
                console.log(response);

            })
            .catch(error => {
                res.json({
                    message: 'An error occured'
                })
            })
    }






    const login = (req,res,next) =>{
       
              var detail = {
            
            register_email:req.body.register_email,
            register_password:req.body.register_password

        } 
        // console.log(detail)

        Employee.find(detail)
        .then(data =>{

            res.json(data)
        // console.log(response)
        

        })

       
    }



    const shaa = (req, res, next) => {
        console.log('hii')
        Currency.find()
        .then(response => {
            res.json({
                response
            })
        })
            .catch(error => {
                res.json({
                    message: 'An error occured'
                })
            }) 
    }



    const results = (req,res,next) =>{

        Users.find()
        .then(response => {
            res.json({
                response
            })
        })
        .catch(error =>{
            console.log(error)
        })
    }


    //add new employee

const store = (req,res,next) => {
  

    let employee = new Employee({
        register_email : req.body.register_email,
        register_password : req.body.register_password,
        register_uname: req.body.register_uname,
        country: req.body.country
       
    })
    // console.log(employee)
    employee.save()
    .then(response => {
        res.json({
            message : 'Employee Added successfully'
        })
    })
    .catch(error =>{
        res.json({
            message : 'An error occured'
        })
    })
}

 // update employee

 const update = (req,res,next) => {
//   console.log('hiii')
    let employeeID = req.body.employeeID

    let updateData = {
        name : req.body.name,
        designation : req.body.designation,
        email: req.body.email,
        phone: req.body.phone,
        age: req.body.age
    }

    Employee.findByIdAndUpdate(employeeID, {$set: updateData})
    .then(()=>{
        res.json({
            message : 'Employee updated successfully'
        })
    })
    .catch(error => {
        res.json({
            message : 'An error occured'
        })
    })

 }


 // new=================

 const newss = (req,res,next)=>{

        let news = new Users ({
            name:req.body.name,
            email:req.body.email,
            sec_name:req.body.sec_name
        })
      
            console.log(newss)
        news.save()
        .then(()=>{
            res.json({
                message:"uses Added Successfully"
            })
        
        })
        .catch(()=>{
            res.json({
                message:"Error occured"
            })
        })

       


 }

 
const liste = (req,res,next) =>{

    Employee.find({})
    .then(response=>{
        res.json({
            response
        })

        var you = response
        console.log(you);
    })


    Countries.find({})
    .then(result=>{
       
            res
        var ne_form = res
        console.log(ne_form);
    })

   
}



  // delete employee

  const destory = (req,res ,next) => {
    let employeeID = req.body.employeeID
    console.log(employeeID)
    Employee.findOneAndRemove(employeeID)
    .then(()=>{
        req.json({
            message : 'Employee deleted successfully'
        })
    })
    .catch(error => {
        res.json({
            message : 'An error occured'
        })
    })
  }

    // aggregation==================

        function perform()
        {
            const collection1 =  Users;
            const collection2 = Countries;


            const result = collection1.aggregate([

                {$match: {id : 1}},

                {
                    $lookup: {
                        from : "collection2",
                        localField:id,
                        foreignField:id,
                        as:"joinedData"
                    }
                },

            ])
            return result.toArray();
        }




  module.exports = {
    index,show,store,update,destory,shows,login,newss,liste,shaa,results,perform,new_find
  }