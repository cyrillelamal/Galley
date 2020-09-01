import React from 'react'
import {Switch, Route} from "react-router-dom";
import Index from "../views/Index";
import Login from "../views/Login";
import Signup from "../views/Signup";
import Dashboard from "../views/Dashboard";
import Logout from "../views/Logout";

class Main extends React.Component {
    render() {
        return (
            <main>
                <Switch>
                    <Route exact path={'/'} component={Index}/>
                    <Route exact path={'/login'} component={Login}/>
                    <Route exact path={'/signup'} component={Signup}/>
                    <Route exact path={'/logout'} component={Logout}/>
                    <Route exact path={'/tasks'} component={Dashboard}/>
                </Switch>
            </main>
        )
    }
}

export default Main;
