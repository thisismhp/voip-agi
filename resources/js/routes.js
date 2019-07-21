import AddCustomer from './components/main/add-customer/AddCustomer'
import CustomersList from './components/main/customers-list/CustomersList'

export const routes = [
    {path:'/', redirect:'/add-customer'},
    {path:'/add-customer', component:AddCustomer},
    {path:'/customer-list', component:CustomersList}
];