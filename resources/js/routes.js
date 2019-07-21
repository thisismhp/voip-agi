import Home from './components/main/home/Home'
import AddCustomer from './components/main/add-customer/AddCustomer'
import CustomersList from './components/main/customers-list/CustomersList'
import DemoUsersList from './components/main/demo-users-list/DemoUsersList'
import ChargeOneCustomer from './components/main/charge-one-customer/ChargeOneCustomer'
import ChargeOneDemoUser from './components/main/charge-one-demo-user/ChargeOneDemoUser'
import ChargeMultiCustomer from './components/main/charge-multi-customer/ChargeMultiCustomer'
import ChargeMultiDemoUser from './components/main/charge-multi-demo-user/ChargeMultiDemoUser'
import NoChargeChecked from './components/main/no-charge-checked-list/NoChargeChecked'
import NoChargeUnchecked from './components/main/no-charge-unchecked-list/NoChargeUnchecked'

export const routes = [
    {path:'/', component:Home},
    {path:'/add-customer', component:AddCustomer},
    {path:'/customer-list', component:CustomersList},
    {path:'/demo-users-list', component:DemoUsersList},
    {path:'/charge-one-customer', component:ChargeOneCustomer},
    {path:'/charge-one-demo-user', component:ChargeOneDemoUser},
    {path:'/charge-multi-customer', component:ChargeMultiCustomer},
    {path:'/charge-multi-demo-user', component:ChargeMultiDemoUser},
    {path:'/no-charge-checked-list', component:NoChargeChecked},
    {path:'/no-charge-unchecked-list', component:NoChargeUnchecked}
];