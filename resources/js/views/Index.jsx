import React from 'react'
import UserContext from "../contexts/UserContext";
import {Link, Redirect} from "react-router-dom";

class Index extends React.Component {
    render() {
        if (this.context) {
            return (
                <Redirect to={'/tasks'}/>
            )
        }
        return (
            <section className="hero is-info is-fullheight">
                <div className="hero-body">
                    <div className="container has-text-centered">
                        <h1 className="title">
                            Galley
                        </h1>
                        <h2 className="subtitle">
                            The powerful list of affairs
                        </h2>
                    </div>
                </div>
                <div className="hero-foot">
                    <nav className="tabs is-boxed is-fullwidth">
                        <div className="container">
                            <ul>
                                <li className="is-active">
                                    <Link to={'/signup'}>Sign up</Link>
                                </li>
                                <li>
                                    <Link to={'/login'}>Login</Link>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </section>
        )
    }
}

Index.contextType = UserContext

export default Index
