import React from 'react'
import axios from 'axios'
import {Link} from "react-router-dom";

class Login extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            email: '',
            password: ''
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
    }

    componentDidMount() {
        this.loadCsrfCookie()
    }

    loadCsrfCookie() {
        axios.get('/sanctum/csrf-cookie')
            .then(res => console.log(res))
    }

    handleChange({target}) {
        const {name, value} = target

        this.setState({[name]: value})
    }

    handleSubmit(e) {
        e.preventDefault()
    }

    render() {
        return (
            <section className="hero is-info is-fullheight">
                <div className="hero-body">
                    <div className="container has-text-centered">
                        <div className="card">
                            <header className="card-header">
                                <p className="card-header-title">
                                    Login
                                </p>
                            </header>
                            <div className="card-content">
                                <form action="" method="post" onSubmit={this.handleSubmit}>
                                    {/* identifier */}
                                    <div className="field">
                                        <p className="control has-icons-left has-icons-right">
                                            <input
                                                className="input" type="email" placeholder="Email"
                                                name="email"
                                                value={this.state.email}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-envelope"/>
                                            </span>
                                            <span className="icon is-small is-right">
                                                <i className="fas fa-check"/>
                                            </span>
                                        </p>
                                    </div>
                                    {/* password */}
                                    <div className="field">
                                        <p className="control has-icons-left has-icons-right">
                                            <input
                                                className="input" type="password" placeholder="Password"
                                                name="password"
                                                value={this.state.password}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-envelope"/>
                                            </span>
                                            <span className="icon is-small is-right">
                                                <i className="fas fa-unlock"/>
                                            </span>
                                        </p>
                                    </div>
                                    {/* submit */}
                                    <div className="field has-text-left">
                                        <p className="control">
                                            <button className="button is-success" role="submit">
                                                Log in
                                            </button>
                                        </p>
                                    </div>

                                </form>
                            </div>
                            {/*<footer className="card-footer">*/}
                            {/*    <div className="card-footer-item">*/}
                            {/*        <Link to={'/signup'}>Or sign up</Link>*/}
                            {/*    </div>*/}
                            {/*</footer>*/}
                        </div>
                    </div>
                </div>
            </section>
        );
    }
}

export default Login
