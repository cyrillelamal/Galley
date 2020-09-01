import React from 'react'
import UserContext from "../contexts/UserContext";
import {Redirect} from "react-router-dom";
import axios from 'axios'
import ListingsContainer from "../components/ListingsContainer";
import TasksContainer from "../components/TasksContainer";

class Dashboard extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tasks: [],
            listings: []
        }
    }

    componentDidMount() {
        this.loadTask()
        // We can generate listings using the related task information
        // this.loadListings()
    }

    loadTask() {
        axios.get('/api/tasks')
            .then(res => res.data)
            .then(tasks => tasks && this.setState({tasks}))
    }

    // We can generate listings using the related task information
    // loadListings() {
    //     axios.get('/api/listings')
    //         .then(res => res.data)
    //         .then(listings => listings && this.setState({listings}))
    // }

    render() {
        const {user} = this.context

        if (!user) {
            return <Redirect to={'/'}/>
        }

        return (
            <section className="hero is-light is-fullheight">
                <div className="columns">
                    <div className="column is-4">
                        <ListingsContainer tasks={this.state.tasks} withForm={true}/>
                    </div>
                    <div className="column is-8">
                        <TasksContainer tasks={this.state.tasks} withForm={true}/>
                    </div>
                </div>
                <h1>section</h1>
            </section>
        )
    }
}

Dashboard.contextType = UserContext

export default Dashboard
