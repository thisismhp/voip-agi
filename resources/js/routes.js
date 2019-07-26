import NotFound from './components/layout/element/NotFound'
import Home from './components/main/home/Home'
import AddCustomer from './components/main/add-customer/AddCustomer'
import CustomersList from './components/main/customers-list/CustomersList'
import CustomerProfile from './components/main/customer-profile/CustomerProfile'
import DemoUsersList from './components/main/demo-users-list/DemoUsersList'
import ChargeOneCustomer from './components/main/charge-one-customer/ChargeOneCustomer'
import ChargeOneDemoUser from './components/main/charge-one-demo-user/ChargeOneDemoUser'
import ChargeMultiCustomer from './components/main/charge-multi-customer/ChargeMultiCustomer'
import ChargeMultiDemoUser from './components/main/charge-multi-demo-user/ChargeMultiDemoUser'
import NoChargeChecked from './components/main/no-charge-checked-list/NoChargeChecked'
import NoChargeUnchecked from './components/main/no-charge-unchecked-list/NoChargeUnchecked'
import AddService from './components/main/add-service/AddService'
import ServicesList from './components/main/services-list/ServicesList'
import AddServiceGroups from './components/main/add-service-groups/AddServiceGroups'
import SymbolsList from './components/main/symbols-list/SymbolsList'

export const routes = [
    {path:'*', component:NotFound},
    {path:'/', component:Home},
    // Customer
    {path:'/add-customer', component:AddCustomer},
    {path:'/customer-list', component:CustomersList},
    {path:'/customer/:id', component:CustomerProfile},
    {path:'/charge-one-customer', component:ChargeOneCustomer},
    {path:'/charge-multi-customer', component:ChargeMultiCustomer},
    {path:'/demo-users-list', component:DemoUsersList},
    {path:'/charge-one-demo-user', component:ChargeOneDemoUser},
    {path:'/charge-multi-demo-user', component:ChargeMultiDemoUser},
    {path:'/no-charge-checked-list', component:NoChargeChecked},
    {path:'/no-charge-unchecked-list', component:NoChargeUnchecked},
    {path:'/add-service', component:AddService},
    {path:'/services-list', component:ServicesList},
    {path:'/add-service-groups', component:AddServiceGroups},
    {path:'/symbols-list', component:SymbolsList},
];
